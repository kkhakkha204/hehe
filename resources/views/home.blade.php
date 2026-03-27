@extends('layouts.app-public')

@section('title', 'Trang chủ')

@section('content')
    <!-- Banner Section -->
    <x-banner-section :banners="$banners" />

    <!-- About Section -->
    <x-about-section />

    <!-- F8-style Courses Tabs Section -->
    <x-home-courses-tabs-section
        :featured-courses="$featuredCourses"
        :free-courses="$freeCourses"
        :latest-courses="$latestCourses"
    />

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
