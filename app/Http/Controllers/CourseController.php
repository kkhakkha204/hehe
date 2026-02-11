<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Banner;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'author'])
            ->where('is_published', true);

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter theo category (nhiều category)
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Sort
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
            default: // newest
                $query->latest();
                break;
        }

        $courses = $query->paginate(6);
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Nếu là AJAX request (load more)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('courses.partials.course-grid', compact('courses'))->render(),
                'hasMore' => $courses->hasMorePages(),
                'nextPage' => $courses->currentPage() + 1
            ]);
        }

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show($slug)
    {
        $course = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Tăng lượt xem
        $course->increment('views');

        // Lấy 3 khóa học liên quan (cùng category, khác khóa hiện tại)
        $relatedCourses = Course::with(['author'])
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Kiểm tra học viên đã mua khóa học chưa
        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('courses.show', compact('course', 'relatedCourses', 'isEnrolled'));
    }
}
