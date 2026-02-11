<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LearningController extends Controller implements HasMiddleware
{
    /**
     * Laravel 12: Define middleware
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Trang học (hiển thị bài học đầu tiên)
     */
    public function show(Course $course)
    {
        // Check đã mua chưa
        if (!auth()->user()->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa mua khóa học này.');
        }

        // Lấy bài học đầu tiên
        $firstLesson = $course->chapters()
            ->with('lessons')
            ->get()
            ->pluck('lessons')
            ->flatten()
            ->first();

        if (!$firstLesson) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Khóa học chưa có nội dung.');
        }

        return redirect()->route('learning.lesson', [
            'course' => $course->slug,
            'lesson' => $firstLesson->id
        ]);
    }

    /**
     * Học bài cụ thể
     */
    public function lesson(Course $course, Lesson $lesson)
    {
        // Check đã mua chưa
        if (!auth()->user()->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn chưa mua khóa học này.');
        }

        // Check lesson có thuộc course không
        $chapter = $lesson->chapter;
        if ($chapter->course_id !== $course->id) {
            abort(404);
        }

        // Load course với chapters và lessons
        $course->load(['chapters.lessons' => function($query) {
            $query->orderBy('sort_order');
        }]);

        return view('learning.player', compact('course', 'lesson', 'chapter'));
    }
}
