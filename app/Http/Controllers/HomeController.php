<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Combo;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy banners active
        $banners = Banner::active()->get();

        // Lấy khóa học miễn phí
        $freeCourses = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('is_published', true)
            ->where(function($query) {
                $query->where('price', 0)
                    ->orWhere('sale_price', 0);
            })
            ->latest()
            ->take(9)
            ->get();

        // Lấy khóa học nổi bật
        $featuredCourses = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->latest()
            ->take(9)
            ->get();

        // Lấy khóa học mới nhất
        $latestCourses = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        $combos = Combo::with(['courses' => function ($query) {
            $query->where('is_published', true);
        }, 'courses.author'])
            ->active()
            ->take(6)
            ->get();

        return view('home', compact('banners', 'freeCourses', 'featuredCourses', 'latestCourses', 'combos'));
    }
}
