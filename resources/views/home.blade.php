@extends('layouts.app-public')

@section('title', 'Trang chủ')

@section('content')
    <!-- Banner Section -->
    <x-banner-section :banners="$banners" />

    <!-- About Section -->
    <x-about-section />

    <!-- Free Courses Section -->
    <x-free-courses-section :courses="$freeCourses" />

    <!-- Featured Courses Section -->
    <x-featured-courses-section :courses="$featuredCourses" />

    <!-- Combo Section -->
    <x-combo-section :combos="$combos" />

    <!-- Testimonials Section -->
    <x-testimonials-section />

    <!-- Illustration Image Section -->
    <x-illustration-section />

    <!-- Risk Free Guarantee Section -->
    <x-guarantee-section />

    <!-- FAQ Section -->
    <x-faq-section />
@endsection
