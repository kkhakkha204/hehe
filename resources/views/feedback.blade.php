@extends('layouts.app-public')

@section('title', 'Cảm nhận học viên')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <style>
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #000;
            opacity: 0.2;
            transition: all 0.3s ease;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #000;
        }
        .video-swiper-pagination .swiper-pagination-bullet {
            background: #000;
        }

        /* Phóng to Fancybox chứa Video lên 80% chiều ngang màn hình */
        .fancybox-custom-video .f-html, 
        .fancybox-custom-video .fancybox__content {
            width: 80vw !important;
            max-width: calc(85vh * 16 / 9) !important; /* Giữ chuẩn tỷ lệ 16:9, không bị méo khi màn hình lùn */
            height: 45vw !important; /* Tỷ lệ 16:9 dựa trên width */
            max-height: 85vh !important;
            padding: 0 !important;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
        }
        .fancybox-custom-video iframe {
            width: 100% !important;
            height: 100% !important;
        }

        /* Trên Mobile thì bung 95% màn hình để dễ xem */
        @media (max-width: 768px) {
            .fancybox-custom-video .f-html, 
            .fancybox-custom-video .fancybox__content {
                width: 95vw !important;
                max-width: none !important;
                height: calc(95vw * 16 / 9) !important; /* Trên mobile nếu là khung chữ nhật dọc */
                max-height: 80vh !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="bg-white py-14 md:py-20 border-t border-gray-100">
    <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
        <h1 class="heading-font text-[48px] md:text-[54px] font-[500] mb-12 text-black leading-none uppercase">Cảm nhận học viên</h1>
        
        <!-- Phần 1: Slider Feedback -->
        <div class="mb-24">
            <div class="swiper feedback-swiper">
                <div class="swiper-wrapper flex !items-stretch">
                    <!-- Slide 1 -->
                    <div class="swiper-slide !h-auto">
                        <div class="w-full h-full flex flex-row items-stretch border border-gray-100 rounded-sm overflow-hidden bg-white">
                            <!-- Ảnh bên trái (30%) -->
                            <div class="w-[30%] shrink-0">
                                <img src="/storage/fb2.webp" alt="Hoàng Thị Hằng" class="w-full h-full object-cover">
                            </div>
                            <!-- Nội dung bên phải (70%) -->
                            <div class="w-[70%] p-4 md:p-8 lg:p-10 flex flex-col justify-start">
                                <h3 class="text-[20px] md:text-[24px] font-bold mb-1 text-black">Hoàng Thị Hằng</h3>
                                <div class="flex text-[#ffb800] mb-3 gap-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <p class="text-[14px] md:text-[15px] text-black/90 leading-relaxed font-medium">
                                    Chưa mua khoá học thì mua ngay đi ạ. Ban đầu cũng phân vân, sợ học qua video thì không biết đường nào mà lần, nhưng lỡ thích style bên Mew Art quá nên đăng ký đại và thật sự là đáng học lắmmmmm. Bạn Hiền dạy trong video rất kĩ, quay góc máy sát mặt nên dễ nhìn lắm, không hề lý thuyết suông đâu. Từ 1 đứa k biết đánh má, hông biết gắn mi, k biết vẽ eyeliner mà cứ tua video làm theo bạn chỉ là ăn ngay. Xịn lắmmmmm.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide !h-auto">
                        <div class="w-full h-full flex flex-row items-stretch border border-gray-100 rounded-sm overflow-hidden bg-white">
                            <!-- Ảnh bên trái (30%) -->
                            <div class="w-[30%] shrink-0">
                                <img src="/storage/fb3.png" alt="Dương Thanh Huyền" class="w-full h-full object-cover">
                            </div>
                            <!-- Nội dung bên phải (70%) -->
                            <div class="w-[70%] p-4 md:p-8 lg:p-10 flex flex-col justify-start">
                                <h3 class="text-[20px] md:text-[24px] font-bold mb-1 text-black">Dương Thanh Huyền</h3>
                                <div class="flex text-[#ffb800] mb-3 gap-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <p class="text-[14px] md:text-[15px] text-black/90 leading-relaxed font-medium">
                                    Chị giảng bài rất dễ hiểu, các bước được chia nhỏ thành từng video ngắn nên người mới như mình rất dễ theo dõi. Chỗ nào chưa thạo mình cứ pause lại rồi thực hành theo. Từ 1 đứa xưa giờ đi đâu vẫn để mặc mộc, mà giờ mình có thể tự tin make sương sương nhờ xem các bài giảng của chị 🥰🥰🥰 Tks chị Hiền
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide !h-auto">
                        <div class="w-full h-full flex flex-row items-stretch border border-gray-100 rounded-sm overflow-hidden bg-white">
                            <div class="w-[30%] shrink-0">
                                <img src="/storage/fb4.jpeg" alt="Mai Nguyễn" class="w-full h-full object-cover">
                            </div>
                            <div class="w-[70%] p-4 md:p-8 lg:p-10 flex flex-col justify-start">
                                <h3 class="text-[20px] md:text-[24px] font-bold mb-1 text-black">Mai Nguyễn</h3>
                                <div class="flex text-[#ffb800] mb-3 gap-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <p class="text-[14px] md:text-[15px] text-black/90 leading-relaxed font-medium">
                                    Thực sự ấn tượng với cách Mew Art hướng dẫn. Mọi thứ từ lý thuyết đến thực hành đều được sắp xếp rất logic, những mẹo nhỏ trong quá trình makeup được giảng viên chia sẻ rất chân thực. Giờ mình tự tin đi tiệc không cần book thợ nữa.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="swiper-slide !h-auto">
                        <div class="w-full h-full flex flex-row items-stretch border border-gray-100 rounded-sm overflow-hidden bg-white">
                            <div class="w-[30%] shrink-0">
                                <img src="/storage/fb5.jpeg" alt="Thảo Nhi" class="w-full h-full object-cover">
                            </div>
                            <div class="w-[70%] p-4 md:p-8 lg:p-10 flex flex-col justify-start">
                                <h3 class="text-[20px] md:text-[24px] font-bold mb-1 text-black">Thảo Nhi</h3>
                                <div class="flex text-[#ffb800] mb-3 gap-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <p class="text-[14px] md:text-[15px] text-black/90 leading-relaxed font-medium">
                                    Mình từng học qua nhiều khóa trên mạng nhưng khóa của chị Hiền là khóa mình thấy ưng nhất. Chị chỉ cho mình cách nhận biết loại da để mua mỹ phẩm sao cho không phí tiền. Cảm ơn chị và đội ngũ nhiều lắm!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="swiper-slide !h-auto">
                        <div class="w-full h-full flex flex-row items-stretch border border-gray-100 rounded-sm overflow-hidden bg-white">
                            <div class="w-[30%] shrink-0">
                                <img src="/storage/fb1.webp" alt="Lê Vy" class="w-full h-full object-cover">
                            </div>
                            <div class="w-[70%] p-4 md:p-8 lg:p-10 flex flex-col justify-start">
                                <h3 class="text-[20px] md:text-[24px] font-bold mb-1 text-black">Lê Vy</h3>
                                <div class="flex text-[#ffb800] mb-3 gap-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <p class="text-[14px] md:text-[15px] text-black/90 leading-relaxed font-medium">
                                    Đáng đồng tiền bát gạo kinh khủng! Các mẹo chị Hiền chỉ siêu thực tế luôn, xem xong video là làm theo được luôn. Chất lượng hình ảnh sắc nét, dễ hình dung cực kỳ. Đặc biệt là cái màn vẽ lông mày, cứu tinh của cuộc đời em! 🥰
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination mt-10 !relative"></div>
            </div>
        </div>
        
    </div>
</div>

<div class="bg-[#ffffff] py-14 md:py-20">
    <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
        <!-- Phần 2: Kết quả sau khóa học -->
        <h2 class="heading-font text-[48px] md:text-[54px] font-[500] mb-12 text-black leading-none uppercase">Kết quả sau khóa học</h2>
        <div class="mb-10">
            <div class="swiper results-swiper overflow-hidden">
                <div class="swiper-wrapper flex items-stretch">
                    @for($i=1; $i<=8; $i++)
                    <div class="swiper-slide h-auto">
                        <a href="/storage/kq{{$i}}.webp" data-fancybox="gallery-results" class="block w-full h-full cursor-zoom-in">
                            <img src="/storage/kq{{$i}}.webp" alt="Kết quả {{$i}}" class="w-full h-auto md:h-auto object-cover">
                        </a>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-[#ffffff] py-14 md:py-20">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-6"> <!-- Thu hẹp chiều ngang để 4 video hiển thị cân đối, không bị quá to -->
        <!-- Phần 3: Hành trình học tập (Video slider) -->
        <h2 class="heading-font text-[48px] md:text-[54px] font-[500] mb-12 text-black leading-none uppercase">Hành trình học tập</h2>
        <div class="mb-10">
            <div class="swiper video-swiper overflow-hidden pb-12">
                <div class="swiper-wrapper">
                    @php
                        // Bạn có thể thay mảng ID Youtube này bằng dữ liệu thực tế từ database
                        $youtubeIds = ['DpUnO0GpqTM', 'V7irQamQvLQ', 'ZhsvVksjY-U', 'WTFSRF8yGzs', 'GzJtcy7W48I'];
                    @endphp
                    @foreach($youtubeIds as $index => $ytId)
                    <div class="swiper-slide rounded-sm overflow-hidden">
                        <a href="https://www.youtube.com/watch?v={{ $ytId }}&controls=0" data-fancybox="gallery-videos" class="relative block w-full aspect-[9/16] bg-zinc-900 group cursor-zoom-in">
                            <!-- Thumbnail từ Youtube (thử link maxresdefault, nếu lỗi tự fallback về hqdefault) -->
                            <img src="https://img.youtube.com/vi/{{ $ytId }}/maxresdefault.jpg" onerror="this.src='https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg'" alt="Video {{ $index + 1 }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <!-- Hiệu ứng phủ đen & Nút Play -->
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                                <div class="w-14 h-14 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-black ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="video-swiper-pagination flex justify-center mt-8 gap-2 !bottom-0 !relative"></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-black py-14 md:py-20" style="background-color: black;">
    <div class="max-w-[1536px] mx-auto px-4 lg:px-6 text-white">
        <!-- Phần 4: Học viên nói gì -->
        <h2 class="heading-font text-[48px] md:text-[54px] font-[500] mb-12 leading-none uppercase text-center md:text-left">Học viên nói gì sau khi học</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @for($i=26; $i<=37; $i++)
            <a href="/storage/Group-{{$i}}.webp" data-fancybox="gallery-chats" class="cursor-zoom-in block">
                <img src="/storage/Group-{{$i}}.webp" alt="Feedback chat {{$i}}" class="w-full h-auto object-cover" onerror="this.src='https://placehold.co/400x800/222/555?text=Chat+{{$i}}'">
            </a>
            @endfor
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Init Fancybox chung cho hình ảnh (kết quả, tin nhắn...)
        Fancybox.bind("[data-fancybox]:not([data-fancybox='gallery-videos'])", {
            Images: {
                zoom: true,
            }
        });

        // Init Fancybox riêng cho video (bung lớn 80% màn hình)
        Fancybox.bind("[data-fancybox='gallery-videos']", {
            mainClass: "fancybox-custom-video",
            compact: false, // Ngăn fancybox tự thu nhỏ trên mobile
            idle: false, // Ngăn tự ẩn thanh công cụ
            Html: {
                youtube: {
                    autoplay: 1,
                    controls: 1, // Để lại control cho người học có thể tua
                    rel: 0
                }
            }
        });

        // Slider 1: Feedback chính
        new Swiper('.feedback-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                }
            }
        });

        // Slider 2: Kết quả sau khóa học (Tự cuộn 3s rồi chuyển)
        new Swiper('.results-swiper', {
            slidesPerView: 2,
            spaceBetween: 10,
            loop: true,
            speed: 800, // tốc độ cuộn khá rề rà mượt mà
            autoplay: {
                delay: 3000, // chờ 3s trước khi cuộn slide tiếp
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 15 },
                1024: { slidesPerView: 5, spaceBetween: 20 }
            },
        });

        // Slider 3: Hành trình học tập (Video reels)
        new Swiper('.video-swiper', {
            slidesPerView: 2,
            spaceBetween: 10,
            loop: true,
            pagination: {
                el: '.video-swiper-pagination',
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active',
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 15 },
                1024: { slidesPerView: 4, spaceBetween: 20 }
            }
        });
    });
</script>
@endpush

