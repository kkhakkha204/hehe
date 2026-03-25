<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $activeTab = $request->query('tab', 'courses');

        if (! in_array($activeTab, ['courses', 'settings'], true)) {
            $activeTab = 'courses';
        }

        $enrollments = $user->enrollments()
            ->with([
                'course.author',
                'course.category',
                'course.chapters.lessons',
            ])
            ->latest('enrolled_at')
            ->get();

        $courses = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            $totalLessons = $course->chapters->sum(fn ($chapter) => $chapter->lessons->count());
            $completedLessons = LessonProgress::query()
                ->where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereNotNull('completed_at')
                ->count();

            $progressPercent = $totalLessons > 0
                ? (int) round(($completedLessons / $totalLessons) * 100)
                : 0;

            $status = 'not_started';

            if ($progressPercent >= 100) {
                $status = 'completed';
            } elseif ($progressPercent > 0) {
                $status = 'in_progress';
            }

            return [
                'enrollment' => $enrollment,
                'course' => $course,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percent' => $progressPercent,
                'status' => $status,
                'action_url' => route('learning.show', $course->slug),
            ];
        });

        $courseStats = [
            'packages' => 0,
            'all' => $courses->count(),
            'completed' => $courses->where('status', 'completed')->count(),
            'in_progress' => $courses->where('status', 'in_progress')->count(),
            'failed' => 0,
        ];

        return view('profile.edit', [
            'user' => $user,
            'activeTab' => $activeTab,
            'courses' => $courses,
            'courseStats' => $courseStats,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit', ['tab' => 'settings'])->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('home');
    }
}
