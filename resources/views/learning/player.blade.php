@extends('layouts.app-public')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">

            <!-- Sidebar: Curriculum -->
            <aside class="w-96 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0">

                <!-- Course Header -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <a href="{{ route('courses.show', $course->slug) }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                    <h2 class="font-bold text-lg text-gray-900">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $course->category->name }}</p>
                </div>

                <!-- Chapters & Lessons -->
                <div class="divide-y divide-gray-100" x-data="{ openChapter: {{ $chapter->id }} }">
                    @foreach($course->chapters as $chapterItem)
                        <div>
                            <!-- Chapter Header -->
                            <button
                                @click="openChapter = openChapter === {{ $chapterItem->id }} ? null : {{ $chapterItem->id }}"
                                class="w-full px-6 py-4 text-left hover:bg-gray-50 transition"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-sm text-gray-900">
                                            {{ $chapterItem->title }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $chapterItem->lessons->count() }} bài học
                                        </p>
                                    </div>
                                    <svg
                                        class="w-5 h-5 text-gray-400 transition-transform"
                                        :class="openChapter === {{ $chapterItem->id }} ? 'rotate-180' : ''"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            <!-- Lessons List -->
                            <div
                                x-show="openChapter === {{ $chapterItem->id }}"
                                x-collapse
                                class="bg-gray-50"
                            >
                                @foreach($chapterItem->lessons as $lessonItem)
                                    <a
                                        href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $lessonItem->id]) }}"
                                        class="block px-6 py-3 hover:bg-gray-100 transition {{ $lessonItem->id === $lesson->id ? 'bg-blue-50 border-l-4 border-blue-600' : '' }}"
                                    >
                                        <div class="flex items-center space-x-3">
                                            <!-- Play Icon -->
                                            <div class="flex-shrink-0">
                                                @if($lessonItem->id === $lesson->id)
                                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Lesson Info -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $lessonItem->title }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ floor($lessonItem->duration / 60) }}:{{ str_pad($lessonItem->duration % 60, 2, '0', STR_PAD_LEFT) }}
                                                </p>
                                            </div>

                                            <!-- Checkmark (TODO: Implement progress tracking) -->
                                            {{-- <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div> --}}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>

            <!-- Main Content: Video Player -->
            <main class="flex-1 flex flex-col overflow-hidden">

                <!-- Video Container -->
                <div class="bg-black flex-shrink-0" style="height: 60vh;">
                    @if($lesson->embed_code)
                        <div class="w-full h-full relative overflow-hidden">
                            <div class="bunny-video-wrapper">
                                {!! $lesson->embed_code !!}
                            </div>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white">
                            <div class="text-center">
                                <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-400">Video chưa được thêm</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Lesson Details -->
                <div class="flex-1 overflow-y-auto bg-white">
                    <div class="max-w-4xl mx-auto p-8">

                        <!-- Title -->
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $lesson->title }}
                        </h1>

                        <!-- Meta Info -->
                        <div class="flex items-center space-x-4 text-sm text-gray-600 mb-6">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ floor($lesson->duration / 60) }} phút {{ $lesson->duration % 60 }} giây
                        </span>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-200">
                            @php
                                // Tìm lesson trước và sau
                                $allLessons = $course->chapters->pluck('lessons')->flatten();
                                $currentIndex = $allLessons->search(function($item) use ($lesson) {
                                    return $item->id === $lesson->id;
                                });
                                $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                                $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                            @endphp

                            <div>
                                @if($prevLesson)
                                    <a
                                        href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $prevLesson->id]) }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Bài trước
                                    </a>
                                @endif
                            </div>

                            <div>
                                @if($nextLesson)
                                    <a
                                        href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                        class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition font-semibold"
                                    >
                                        Bài tiếp theo
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <div class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Hoàn thành khóa học
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Lesson Content -->
                        @if($lesson->content)
                            <div class="prose max-w-none">
                                {!! $lesson->content !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">Chưa có nội dung bổ sung.</p>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('styles')
        <style>
            /* Override Bunny.net embed styles */
            .bunny-video-wrapper {
                position: relative;
                width: 100%;
                height: 100%;
            }

            .bunny-video-wrapper > div {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                padding-top: 0 !important; /* Remove Bunny's padding-top */
            }

            .bunny-video-wrapper iframe {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        </style>
    @endpush
@endsection

@push('scripts')
    <script>
        // TODO: Implement progress tracking
        // Khi video play đến 90% → gọi API đánh dấu hoàn thành
    </script>
@endpush
