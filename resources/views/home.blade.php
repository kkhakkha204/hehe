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
@endsection
