@extends('layouts.app-public')

@section('title', 'Danh sách khóa học')

@section('content')
    @if(($combos ?? collect())->count())
        <x-combo-section :combos="$combos" />
    @endif

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16" x-data="courseFilter()">
        <!-- Mobile Filter Button -->
        <button
            @click="toggleMobileFilter()"
            class="lg:hidden fixed bottom-6 right-6 z-40 bg-black text-white p-4 rounded-full shadow-lg"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
        </button>

        <!-- Mobile Filter Overlay -->
        <div
            x-show="mobileFilterOpen"
            @click="closeMobileFilter()"
            class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
            style="display: none;"
        ></div>

        <!-- Mobile Filter Sidebar -->
        <aside
            id="mobile-filter"
            class="lg:hidden fixed left-0 top-0 h-full w-80 bg-black text-white z-50 overflow-y-auto transform -translate-x-full"
        >
            <div class="p-6 space-y-6">
                <!-- Close Button -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Bộ lọc</h3>
                    <button @click="closeMobileFilter()" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Tìm kiếm</label>
                    <input
                        type="text"
                        x-model="filters.search"
                        @input.debounce.500ms="applyFilters()"
                        placeholder="Nhập từ khóa..."
                        class="w-full px-4 py-2 bg-white text-black rounded-md focus:outline-none"
                    >
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Sắp xếp theo</label>
                    <select
                        x-model="filters.sort"
                        @change="applyFilters()"
                        class="w-full px-4 py-2 bg-white text-black rounded-md focus:outline-none"
                    >
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-3">Danh mục</label>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    value="{{ $category->id }}"
                                    x-model="filters.categories"
                                    @change="applyFilters()"
                                    class="filter-checkbox"
                                >
                                <span class="text-sm group-hover:text-gray-300">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Level Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-3">Level</label>
                    <div class="space-y-2">
                        @foreach([1, 2, 3] as $level)
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    value="{{ $level }}"
                                    x-model="filters.levels"
                                    @change="applyFilters()"
                                    class="filter-checkbox"
                                >
                                <span class="text-sm group-hover:text-gray-300">Level {{ $level }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Reset Filters -->
                <button
                    @click="resetFilters(); closeMobileFilter();"
                    class="w-full px-4 py-2 border border-white rounded-md hover:bg-white hover:text-black transition text-sm font-medium"
                >
                    Xóa bộ lọc
                </button>
            </div>
        </aside>

        <div class="flex gap-8">
            <!-- Desktop Sidebar Filter -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-24 bg-black text-white p-6 rounded-md space-y-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">Tìm kiếm</label>
                        <input
                            type="text"
                            x-model="filters.search"
                            @input.debounce.500ms="applyFilters()"
                            placeholder="Nhập từ khóa..."
                            class="w-full px-4 py-2 bg-white text-black rounded-md focus:outline-none"
                        >
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">Sắp xếp theo</label>
                        <select
                            x-model="filters.sort"
                            @change="applyFilters()"
                            class="w-full px-4 py-2 bg-white text-black rounded-md focus:outline-none"
                        >
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-3">Danh mục</label>
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        value="{{ $category->id }}"
                                        x-model="filters.categories"
                                        @change="applyFilters()"
                                        class="filter-checkbox"
                                    >
                                    <span class="text-sm group-hover:text-gray-300">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-3">Level</label>
                        <div class="space-y-2">
                            @foreach([1, 2, 3] as $level)
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        value="{{ $level }}"
                                        x-model="filters.levels"
                                        @change="applyFilters()"
                                        class="filter-checkbox"
                                    >
                                    <span class="text-sm group-hover:text-gray-300">Level {{ $level }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reset Filters -->
                    <button
                        @click="resetFilters()"
                        class="w-full px-4 py-2 border border-white rounded-md hover:bg-white hover:text-black transition text-sm font-medium"
                    >
                        Xóa bộ lọc
                    </button>
                </div>
            </aside>

            <!-- Courses Grid -->
            <div class="flex-1">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold">Khóa học</h1>
                    <p class="text-gray-600 mt-2" x-text="'Tìm thấy ' + totalCourses + ' khóa học'"></p>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black"></div>
                </div>

                <!-- Courses Grid Container -->
                <div id="courses-container" x-show="!loading">
                    @include('courses.partials.course-grid', ['courses' => $courses])
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-12" x-show="hasMore && !loading">
                    <button
                        @click="loadMore()"
                        class="px-8 py-3 bg-black text-white border-2 border-black rounded-md transition font-medium"
                    >
                        Xem thêm
                    </button>
                </div>

                <!-- No Results -->
                <div x-show="!loading && totalCourses === 0" class="text-center py-12">
                    <p class="text-gray-500">Không tìm thấy khóa học nào.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .filter-checkbox {
            -webkit-appearance: none;
            appearance: none;
            width: 19px;
            height: 19px;
            background: #ffffff;
            border: 1px solid #ffffff;
            border-radius: 0;
            display: inline-block;
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
        }

        .filter-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 1px;
            width: 5px;
            height: 11px;
            border: solid #111111;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .filter-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.35);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function courseFilter() {
            return {
                filters: {
                    search: '{{ request("search") }}',
                    sort: '{{ request("sort", "newest") }}',
                    categories: @json(request('categories', [])),
                    levels: @json(request('levels', []))
                },
                loading: false,
                hasMore: {{ $courses->hasMorePages() ? 'true' : 'false' }},
                currentPage: 1,
                totalCourses: {{ $courses->total() }},
                mobileFilterOpen: false,

                toggleMobileFilter() {
                    if (this.mobileFilterOpen) {
                        this.closeMobileFilter();
                    } else {
                        this.openMobileFilter();
                    }
                },

                openMobileFilter() {
                    this.mobileFilterOpen = true;
                    const sidebar = document.getElementById('mobile-filter');
                    gsap.to(sidebar, {
                        x: 0,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                },

                closeMobileFilter() {
                    const sidebar = document.getElementById('mobile-filter');
                    gsap.to(sidebar, {
                        x: '-100%',
                        duration: 0.3,
                        ease: 'power2.in',
                        onComplete: () => {
                            this.mobileFilterOpen = false;
                        }
                    });
                },

                applyFilters() {
                    this.loading = true;
                    this.currentPage = 1;

                    const params = new URLSearchParams({
                        search: this.filters.search,
                        sort: this.filters.sort,
                        page: 1
                    });

                    this.filters.categories.forEach(cat => {
                        params.append('categories[]', cat);
                    });

                    this.filters.levels.forEach(level => {
                        params.append('levels[]', level);
                    });

                    fetch(`{{ route('courses.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('courses-container').innerHTML = data.html;
                            this.hasMore = data.hasMore;
                            this.currentPage = 1;
                            this.loading = false;

                            const countMatch = data.html.match(/data-total="(\d+)"/);
                            if (countMatch) {
                                this.totalCourses = parseInt(countMatch[1]);
                            }
                        });
                },

                loadMore() {
                    this.loading = true;
                    const nextPage = this.currentPage + 1;

                    const params = new URLSearchParams({
                        search: this.filters.search,
                        sort: this.filters.sort,
                        page: nextPage
                    });

                    this.filters.categories.forEach(cat => {
                        params.append('categories[]', cat);
                    });

                    this.filters.levels.forEach(level => {
                        params.append('levels[]', level);
                    });

                    fetch(`{{ route('courses.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            const container = document.getElementById('courses-container');
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data.html;
                            const newGrid = tempDiv.querySelector('.grid');

                            container.querySelector('.grid').insertAdjacentHTML('beforeend', newGrid.innerHTML);

                            this.hasMore = data.hasMore;
                            this.currentPage = nextPage;
                            this.loading = false;
                        });
                },

                resetFilters() {
                    this.filters = {
                        search: '',
                        sort: 'newest',
                        categories: [],
                        levels: []
                    };
                    this.applyFilters();
                }
            }
        }
    </script>
@endpush
