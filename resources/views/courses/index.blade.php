@extends('layouts.app-public')

@section('title', 'Danh sách khóa học')

@section('content')
    @if(($combos ?? collect())->count())
        <x-combo-section :combos="$combos" />
    @endif

    <section class="course-index-page bg-[#fdf9f4] py-8 md:py-10" x-data="courseFilter()">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-8">
            <div class="course-index-hero hidden mb-6 rounded-[28px] border border-[#eadfce] bg-white/90 p-5 shadow-[0_14px_36px_rgba(23,23,23,0.05)]">
                <div class="max-w-[780px]">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#f4ede3] px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#b08d5e]">
                        <span class="h-2 w-2 rounded-full bg-[#d4956a]"></span>
                        Học viện Mew Art
                    </span>
                    <h1 class="mt-3 heading-font text-[34px] uppercase leading-none text-[#171717] md:text-[46px]">Khóa học Makeup</h1>
                    <p class="mt-3 max-w-[640px] text-[13px] leading-6 text-[#6b6b6b]">
                        Giao diện danh sách khóa học đã được làm lại theo bộ HTML sếp gửi: nhìn gọn hơn, sang hơn và gần tinh thần nền tảng học online như F8.
                    </p>
                </div>

            </div>

            <button
                @click="toggleMobileFilter()"
                class="lg:hidden fixed bottom-6 right-6 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-black text-white shadow-xl"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </button>

            <div
                x-show="mobileFilterOpen"
                @click="closeMobileFilter()"
                class="lg:hidden fixed inset-0 z-40 bg-black/50"
                style="display: none;"
            ></div>

            <aside
                id="mobile-filter"
                class="lg:hidden fixed left-0 top-0 z-50 h-full w-[320px] max-w-[90vw] -translate-x-full overflow-y-auto bg-[#171717] text-white"
            >
                <div class="space-y-6 p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold">Bộ lọc khóa học</h3>
                        <button @click="closeMobileFilter()" class="text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Tìm kiếm</label>
                        <input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters()" placeholder="Nhập tên khóa học..." class="w-full rounded-2xl border border-white/15 bg-white px-4 py-3 text-[14px] text-black focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Sắp xếp theo</label>
                        <select x-model="filters.sort" @change="applyFilters()" class="w-full rounded-2xl border border-white/15 bg-white px-4 py-3 text-[14px] text-black focus:outline-none">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold">Danh mục</label>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" value="{{ $category->id }}" x-model="filters.categories" @change="applyFilters()" class="filter-checkbox">
                                    <span class="text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold">Level</label>
                        <div class="space-y-2">
                            @foreach([1, 2, 3] as $level)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" value="{{ $level }}" x-model="filters.levels" @change="applyFilters()" class="filter-checkbox">
                                    <span class="text-sm">Level {{ $level }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button @click="resetFilters(); closeMobileFilter();" class="w-full rounded-2xl border border-white/25 px-4 py-3 text-sm font-semibold">
                        Xóa bộ lọc
                    </button>
                </div>
            </aside>

            <div class="grid gap-5 lg:grid-cols-[270px_minmax(0,1fr)]">
                <aside class="hidden lg:block">
                    <div class="sticky top-24 rounded-[24px] border border-[#eadfce] bg-[#171717] p-5 text-white shadow-[0_16px_40px_rgba(23,23,23,0.1)]">
                        <div class="mb-5">
                            <p class="text-[12px] font-semibold uppercase tracking-[0.2em] text-[#e8d5b8]">Bộ lọc</p>
                            <h2 class="mt-2 heading-font text-[24px] uppercase leading-none">Khóa học</h2>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="mb-2 block text-[13px] font-semibold">Tìm kiếm</label>
                                <input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters()" placeholder="Nhập tên khóa học..." class="w-full rounded-2xl border border-white/15 bg-white px-4 py-2.5 text-[13px] text-black focus:outline-none">
                            </div>

                            <div>
                                <label class="mb-2 block text-[13px] font-semibold">Sắp xếp theo</label>
                                <select x-model="filters.sort" @change="applyFilters()" class="w-full rounded-2xl border border-white/15 bg-white px-4 py-2.5 text-[13px] text-black focus:outline-none">
                                    <option value="newest">Mới nhất</option>
                                    <option value="oldest">Cũ nhất</option>
                                    <option value="price_asc">Giá tăng dần</option>
                                    <option value="price_desc">Giá giảm dần</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-3 block text-[13px] font-semibold">Danh mục</label>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($categories as $category)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $category->id }}" x-model="filters.categories" @change="applyFilters()" class="filter-checkbox">
                                            <span class="text-[13px]">{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="mb-3 block text-[13px] font-semibold">Level</label>
                                <div class="space-y-2">
                                    @foreach([1, 2, 3] as $level)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $level }}" x-model="filters.levels" @change="applyFilters()" class="filter-checkbox">
                                            <span class="text-[13px]">Level {{ $level }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button @click="resetFilters()" class="w-full rounded-2xl border border-white/25 px-4 py-2.5 text-[13px] font-semibold transition hover:bg-white hover:text-black">
                                Xóa bộ lọc
                            </button>
                        </div>
                    </div>
                </aside>

                <div>
                    <div class="course-index-toolbar mb-5 flex flex-col gap-2.5 md:flex-row md:items-end md:justify-between">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-[#f4ede3] px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#b08d5e]">
                                <span class="h-2 w-2 rounded-full bg-[#d4956a]"></span>
                                Danh sách học tập
                            </span>
                            <h2 class="mt-3 heading-font text-[28px] uppercase leading-none text-[#171717] md:text-[34px]">Chọn khóa học phù hợp</h2>
                            <p class="mt-2 text-[13px] text-[#6b6b6b]" x-text="'Hiện có ' + totalCourses + ' khóa học phù hợp với bộ lọc của anh'"></p>
                        </div>
                    </div>

                    <div x-show="loading" class="py-14 text-center">
                        <div class="inline-block h-10 w-10 animate-spin rounded-full border-[3px] border-[#eadfce] border-t-black"></div>
                    </div>

                    <div id="courses-container" x-show="!loading">
                        @include('courses.partials.course-grid', ['courses' => $courses])
                    </div>

                    <div class="mt-8 text-center" x-show="hasMore && !loading">
                        <button @click="loadMore()" class="inline-flex items-center justify-center rounded-full bg-black px-7 py-2.5 text-[13px] font-semibold text-white transition hover:opacity-90">
                            Xem thêm khóa học
                        </button>
                    </div>

                    <div x-show="!loading && totalCourses === 0" class="rounded-[24px] border border-[#eadfce] bg-white px-6 py-12 text-center">
                        <h3 class="heading-font text-[24px] uppercase text-[#171717]">Không tìm thấy khóa học</h3>
                        <p class="mt-3 text-[14px] text-[#7b7b7b]">Thử đổi từ khóa tìm kiếm hoặc bỏ bớt bộ lọc để xem thêm kết quả.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .course-index-page .course-index-hero h1 {
            font-size: clamp(1.85rem, 3.3vw, 2.5rem);
            line-height: .96;
        }

        .course-index-page .course-index-hero p,
        .course-index-page .course-index-toolbar p {
            font-size: .8125rem;
        }

        .course-index-page .course-index-toolbar h2 {
            font-size: clamp(1.55rem, 2.6vw, 1.95rem);
            line-height: .98;
        }

        .course-index-page .course-grid-root {
            gap: 1rem;
        }

        .course-index-page .filter-checkbox {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid rgba(255,255,255,0.45);
            background: #fff;
            border-radius: 5px;
            position: relative;
            flex-shrink: 0;
            cursor: pointer;
        }

        .course-index-page .filter-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 5px;
            height: 10px;
            border: solid #111;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
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
                    gsap.to(sidebar, { x: 0, duration: 0.3, ease: 'power2.out' });
                },

                closeMobileFilter() {
                    const sidebar = document.getElementById('mobile-filter');
                    gsap.to(sidebar, {
                        x: '-100%',
                        duration: 0.3,
                        ease: 'power2.in',
                        onComplete: () => { this.mobileFilterOpen = false; }
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

                    this.filters.categories.forEach(cat => params.append('categories[]', cat));
                    this.filters.levels.forEach(level => params.append('levels[]', level));

                    fetch(`{{ route('courses.index') }}?${params.toString()}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

                    this.filters.categories.forEach(cat => params.append('categories[]', cat));
                    this.filters.levels.forEach(level => params.append('levels[]', level));

                    fetch(`{{ route('courses.index') }}?${params.toString()}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data.html;
                            const newCourses = tempDiv.querySelector('.course-grid-root');
                            const currentGrid = document.querySelector('.course-grid-root');

                            if (newCourses && currentGrid) {
                                const courseElements = newCourses.children;
                                Array.from(courseElements).forEach((element) => {
                                    if (!element.classList.contains('course-grid-empty')) {
                                        currentGrid.appendChild(element);
                                    }
                                });
                            }

                            this.hasMore = data.hasMore;
                            this.currentPage = nextPage;
                            this.loading = false;

                            const countMatch = data.html.match(/data-total="(\d+)"/);
                            if (countMatch) {
                                this.totalCourses = parseInt(countMatch[1]);
                            }
                        });
                },

                resetFilters() {
                    this.filters.search = '';
                    this.filters.sort = 'newest';
                    this.filters.categories = [];
                    this.filters.levels = [];
                    this.applyFilters();
                }
            }
        }
    </script>
@endpush
