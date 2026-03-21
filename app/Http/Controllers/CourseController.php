<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Combo;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'author'])
            ->where('is_published', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->filled('levels')) {
            $query->whereIn('level', $request->levels);
        }

        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $courses = $query->paginate(6);
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $combos = Combo::with([
            'courses' => function ($query) {
                $query->where('is_published', true)
                    ->with('author')
                    ->orderBy('title');
            },
        ])
            ->active()
            ->take(12)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('courses.partials.course-grid', compact('courses'))->render(),
                'hasMore' => $courses->hasMorePages(),
                'nextPage' => $courses->currentPage() + 1,
            ]);
        }

        return view('courses.index', compact('courses', 'categories', 'combos'));
    }

    public function show($slug)
    {
        $course = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $course->increment('views');

        $relatedCourses = Course::with(['author'])
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $isEnrolled = false;
        $progressPercent = 0;
        $resumeLesson = null;

        if (auth()->check()) {
            $user = auth()->user();

            $isEnrolled = $user->enrollments()
                ->where('course_id', $course->id)
                ->exists();

            if ($isEnrolled) {
                $completedLessons = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $totalLessons = $course->chapters->pluck('lessons')->flatten()->count();

                $progressPercent = $totalLessons > 0
                    ? (int) round(($completedLessons / $totalLessons) * 100)
                    : 0;

                $resumeLesson = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->latest('last_viewed_at')
                    ->with('lesson')
                    ->first()
                    ?->lesson;
            }
        }

        return view('courses.show', compact('course', 'relatedCourses', 'isEnrolled', 'progressPercent', 'resumeLesson'));
    }
}
