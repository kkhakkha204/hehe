<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LearningController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function show(Course $course)
    {
        $user = auth()->user();

        if (! $user->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa mua khóa học này.');
        }

        $course->load(['chapters.lessons' => fn ($query) => $query->orderBy('sort_order')]);

        $resumeLesson = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->latest('last_viewed_at')
            ->with('lesson')
            ->first()
            ?->lesson;

        $firstLesson = $course->chapters->pluck('lessons')->flatten()->first();
        $targetLesson = $resumeLesson ?? $firstLesson;

        if (! $targetLesson) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Khóa học chưa có nội dung.');
        }

        return redirect()->route('learning.lesson', [
            'course' => $course->slug,
            'lesson' => $targetLesson->id,
        ]);
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        if (! $user->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa mua khóa học này.');
        }

        $chapter = $lesson->chapter;

        if ($chapter->course_id !== $course->id) {
            abort(404);
        }

        $course->load(['chapters.lessons' => fn ($query) => $query->orderBy('sort_order')]);

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'first_viewed_at' => now(),
                'last_viewed_at' => now(),
            ]
        );

        $progress->forceFill([
            'last_viewed_at' => now(),
            'first_viewed_at' => $progress->first_viewed_at ?? now(),
        ])->save();

        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNotNull('completed_at')
            ->count();

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->all();

        $totalLessons = $course->chapters->pluck('lessons')->flatten()->count();
        $progressPercent = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        $currentLessonWatchedSeconds = (int) $progress->watched_seconds;

        return view('learning.player', compact(
            'course',
            'lesson',
            'chapter',
            'progressPercent',
            'completedLessons',
            'completedLessonIds',
            'totalLessons',
            'currentLessonWatchedSeconds'
        ));
    }

    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        $user = $request->user();

        if (! $user || ! $user->hasEnrolled($course->id)) {
            abort(403);
        }

        if ($lesson->chapter?->course_id !== $course->id) {
            abort(404);
        }

        $data = $request->validate([
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
            'mark_completed' => ['nullable', 'boolean'],
        ]);

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'first_viewed_at' => now(),
            ]
        );

        $progress->watched_seconds = max(
            (int) $progress->watched_seconds,
            (int) ($data['watched_seconds'] ?? 0)
        );
        $progress->first_viewed_at = $progress->first_viewed_at ?? now();
        $progress->last_viewed_at = now();

        if (($data['mark_completed'] ?? false) && ! $progress->completed_at) {
            $progress->completed_at = now();
        }

        $progress->save();

        return response()->json([
            'ok' => true,
            'watched_seconds' => $progress->watched_seconds,
            'completed' => (bool) $progress->completed_at,
        ]);
    }
}
