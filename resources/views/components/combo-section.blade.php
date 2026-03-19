@props(['combos' => []])

<section class="combo-section bg-[#0f0f0f] py-12 md:py-16">
    <div class="container max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title -->
        <h2 class="heading-font text-[34px] font-normal text-white uppercase text-center md:text-start mb-8 md:mb-12 leading-none">
            Các gói combo ưu đãi
        </h2>

        @if($combos->count())
            @php
                $comboSlides = $combos->chunk(3)->values();
            @endphp

            <div x-data="{ active: 0 }">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500" :style="`transform: translateX(-${active * 100}%);`">
                        @foreach($comboSlides as $slide)
                            <div class="w-full shrink-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($slide as $combo)
                                        @php
                                            $comboUrl = route('combos.show', $combo->slug);
                                            $authors = $combo->courses
                                                ->pluck('author')
                                                ->filter()
                                                ->unique('id')
                                                ->take(2);
                                        @endphp
                                        <article class="combo-card bg-[#111111] border border-[#2d2d2d] rounded-md overflow-hidden flex flex-col">
                                            <div class="relative">
                                                <a href="{{ $comboUrl }}" class="block" aria-label="Xem chi tiết combo {{ $combo->title }}">
                                                    <img
                                                        src="{{ $combo->thumbnail_url ?? asset('storage/nen.webp') }}"
                                                        alt="{{ $combo->title }}"
                                                        class="w-full aspect-[16/9] object-cover"
                                                        loading="lazy"
                                                    >
                                                </a>

                                                @if($combo->sale_price && $combo->sale_price < $combo->price)
                                                    <div class="absolute right-0 bottom-0 bg-[#d4af37] text-white px-4 py-1.5 flex items-center gap-2 text-[15px]">
                                                        <span class="line-through text-white/80">{{ number_format($combo->price, 0, ',', '.') }}đ</span>
                                                        <span class="font-semibold">{{ number_format($combo->sale_price, 0, ',', '.') }}đ</span>
                                                    </div>
                                                @elseif($combo->price)
                                                    <div class="absolute right-0 bottom-0 bg-[#d4af37] text-white px-4 py-1.5 text-[15px] font-semibold">
                                                        {{ number_format($combo->price, 0, ',', '.') }}đ
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="p-5 flex flex-col gap-3 flex-1">
                                                <h3 class="text-white text-[22px] uppercase leading-tight">
                                                    <a href="{{ $comboUrl }}" class="hover:text-[#d4af37] transition-colors">{{ $combo->title }}</a>
                                                </h3>

                                                @if($combo->description)
                                                    <p class="text-[#d0d0d0] text-[15px] line-clamp-2">{{ $combo->description }}</p>
                                                @endif

                                                <div class="mt-auto flex items-center justify-between gap-4 pt-3">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        @forelse($authors as $author)
                                                            <div class="flex items-center gap-2 min-w-0">
                                                                <img
                                                                    src="{{ $author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=111&color=fff' }}"
                                                                    alt="{{ $author->name }}"
                                                                    class="w-8 h-8 rounded-full object-cover border border-white/20"
                                                                >
                                                                <span class="text-[#e9e9e9] text-[15px] truncate">{{ $author->name }}</span>
                                                            </div>
                                                        @empty
                                                            <span class="text-[#b8b8b8] text-[14px]">Chưa có giảng viên</span>
                                                        @endforelse
                                                    </div>

                                                    <a href="{{ $comboUrl }}" class="inline-flex items-center gap-2 text-white text-[15px] uppercase hover:text-[#d4af37] transition-colors">
                                                        Chi tiết
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($comboSlides->count() > 1)
                    <div class="mt-6 flex items-center justify-center gap-2">
                        @foreach($comboSlides as $index => $slide)
                            <button
                                type="button"
                                @click="active = {{ $index }}"
                                class="w-2.5 h-2.5 rounded-full transition-colors"
                                :class="active === {{ $index }} ? 'bg-white' : 'bg-white/30'"
                                aria-label="Tới slide combo {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="border border-white/20 rounded-md p-8 text-center text-white/70 text-[15px]">
                Chưa có combo khóa học nào được hiển thị.
            </div>
        @endif
    </div>
</section>