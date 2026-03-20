@extends('layouts.app-public')

@section('title', 'Giới thiệu')

@section('content')
    <section class="bg-[#ffffff] py-12 md:py-16">
        <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_0.85fr] gap-8 lg:gap-10 items-center">
                <div class="rounded-sm overflow-hidden">
                    <img
                        src="storage/video-1536x869.webp"
                        alt="Mewart team"
                        class="w-full h-full object-cover"
                    >
                </div>

                <div>
                    <h1 class="heading-font font-[500] text-[42px] md:text-[46px] leading-none uppercase text-black mb-5">MEWART MAKE UP</h1>
                    <p class="text-[22px] md:text-[24px] text-black leading-tight mb-4"><strong>Mew Art</strong> – Nơi bắt đầu của những Makeup Artist tự tin &amp; chuyên nghiệp</p>
                    <p class="text-[16px] md:text-[17px] text-black/90 leading-relaxed mb-4">
                        Mew Art Makeup Academy là học viện đào tạo trang điểm được sáng lập và trực tiếp giảng dạy bởi Hiền Mew – Makeup Artist với hơn 6 năm kinh nghiệm thực chiến trong ngành làm đẹp.
                    </p>
                    <p class="text-[16px] md:text-[17px] text-black/90 leading-relaxed">
                        Với định hướng đào tạo bài bản – thực tế – cập nhật xu hướng, Mew Art không chỉ dạy bạn cách trang điểm đẹp, mà còn giúp bạn hiểu rõ bản thân, làm chủ kỹ năng và tự tin phát triển con đường riêng trong nghề makeup.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#ffffff] pb-12 md:pb-16">
        <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
                <div>
                    <h2 class="heading-font font-[500] text-[54px] md:text-[62px] leading-none uppercase text-black mb-4">Sứ mệnh</h2>
                    <p class="text-[18px] md:text-[20px] text-black font-semibold mb-4">Xây dựng thế hệ Makeup Artist trẻ – vững tay nghề – rõ tư duy</p>

                    <p class="text-[16px] md:text-[17px] font-semibold mb-2">Mew Art Makeup Academy ra đời với sứ mệnh:</p>
                    <ul class="list-disc pl-6 space-y-1 text-[16px] md:text-[17px] font-semibold text-black/90">
                        <li>Giúp phụ nữ tự tin makeup mỗi ngày, dù là người mới bắt đầu</li>
                        <li>Đào tạo Makeup Artist chuyên nghiệp, có thể làm nghề bền vững</li>
                        <li>Chuẩn hóa kiến thức – quy trình – tư duy làm nghề một cách nghiêm túc</li>
                    </ul>

                    <p class="text-[16px] md:text-[17px] font-semibold mt-5 mb-2">Sau hơn 7 năm phát triển, Mew Art đã:</p>
                    <ul class="list-disc pl-6 space-y-1 text-[16px] md:text-[17px] font-semibold text-black/90">
                        <li>Đồng hành cùng 5.600+ học viên trên toàn quốc</li>
                        <li>Xây dựng cộng đồng hơn 589.000 người theo dõi, trong đó 240.000+ followers TikTok, nhiều video đạt triệu lượt xem</li>
                    </ul>
                </div>

                <div class="flex items-center justify-center lg:justify-end gap-5 md:gap-7">
                    <div class="w-[220px] h-[220px] md:w-[270px] md:h-[270px] rounded-full border-2 border-black flex items-center justify-center text-black gap-3 md:gap-4 px-5">
                        <p class="heading-font font-bold text-[48px] md:text-[60px] leading-none shrink-0">6</p>
                        <div class="heading-font font-bold text-[24px] md:text-[30px] leading-[1.05] text-left uppercase">
                            <p class="whitespace-nowrap">Năm</p>
                            <p class="whitespace-nowrap">Kinh nghiệm</p>
                        </div>
                    </div>
                    <div class="w-[220px] h-[220px] md:w-[270px] md:h-[270px] rounded-full border-2 border-black flex items-center justify-center text-black gap-3 md:gap-4 px-5">
                        <p class="heading-font font-bold text-[44px] md:text-[56px] leading-none shrink-0">5,600</p>
                        <div class="heading-font font-bold text-[24px] md:text-[30px] leading-[1.05] text-left uppercase">
                            <p class="whitespace-nowrap">Học</p>
                            <p class="whitespace-nowrap">Viên</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-black py-14 md:py-16" style="background-color: black;">
        <div class="max-w-[1536px] mx-auto px-4 lg:px-6 text-white">
            <h2 class="heading-font text-[62px] md:text-[78px] leading-none uppercase text-center mb-10">Điều gì tạo nên sự khác biệt của Mew Art Makeup Academy?</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <p class="heading-font text-[98px] text-white/35 leading-none mb-3">01</p>
                    <h3 class="heading-font text-[28px] leading-tight uppercase mb-4">Nền tảng uy tín – đào tạo nghiêm túc</h3>
                    <p class="text-[22px] leading-relaxed text-white/90">Mew Art Makeup Academy tập trung vào chất lượng đào tạo thực chất, không chạy theo số lượng. Chương trình học được xây dựng rõ ràng, có lộ trình, có mục tiêu đầu ra cho từng học viên.</p>
                </div>
                <div>
                    <p class="heading-font text-[98px] text-white/35 leading-none mb-3">02</p>
                    <h3 class="heading-font text-[28px] leading-tight uppercase mb-4">Lý thuyết đi đôi với thực hành</h3>
                    <p class="text-[22px] leading-relaxed text-white/90">Học viên được thực hành liên tục ngay tại lớp, bám sát từng bước: Hiểu da – hiểu khuôn mặt – hiểu sản phẩm. Ứng dụng trực tiếp vào từng layout makeup. Được chỉnh sửa chi tiết từng lỗi nhỏ.</p>
                    <p class="text-[22px] leading-relaxed text-white font-semibold mt-2">Không học chay – không học thuộc – học để làm được.</p>
                </div>
                <div>
                    <p class="heading-font text-[98px] text-white/35 leading-none mb-3">03</p>
                    <h3 class="heading-font text-[28px] leading-tight uppercase mb-4">Giáo trình độc quyền – không rập khuôn</h3>
                    <p class="text-[22px] leading-relaxed text-white/90">Giáo trình tại Mew Art được biên soạn độc quyền, đúc kết từ kinh nghiệm thực tế nhiều năm làm nghề. Quá trình đào tạo và sửa bài cho hàng ngàn học viên Mew Art không áp đặt tư duy sáng tạo.</p>
                </div>
                <div>
                    <p class="heading-font text-[98px] text-white/35 leading-none mb-3">04</p>
                    <h3 class="heading-font text-[28px] leading-tight uppercase mb-4">Định hướng phát triển lâu dài</h3>
                    <p class="text-[22px] leading-relaxed text-white/90">Sau khi hoàn thành khóa học, học viên được cấp chứng nhận hoàn thành khóa học. Được hỗ trợ định hướng làm nghề, phát triển cá nhân. Có nền tảng để tự làm freelance, mở studio hoặc theo nghề lâu dài.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#ffffff] py-14 md:py-16">
        <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
            <div class="flex flex-row gap-4 lg:gap-12 items-end relative">
                <div class="w-[60%] lg:w-1/2">
                    <h2 class="heading-font text-[32px] md:text-[45px] lg:text-[45px] leading-none uppercase text-black mb-4 md:mb-6"><b>Câu chuyện người sáng lập</b></h2>
                    
                    <!-- Text on Mobile -->
                    <div class="space-y-3 text-[14px] text-black/90 leading-relaxed md:hidden">
                        <p>Năm 2018, Hiền phải trang điểm thường xuyên cho công việc nhưng makeup nhanh mốc, loang lổ do thiếu kiến thức về da và sản phẩm. Những dịp quan trọng, việc thuê thợ vừa tốn kém vừa khó đặt lịch. Từ đó, Hiền nhận ra:</p>
                        <p><strong>Makeup không chỉ là đẹp – mà cần đúng kỹ thuật và hiểu bản chất.</strong></p>
                    </div>

                    <!-- Text on Desktop/Tablet -->
                    <div class="hidden md:block md:space-y-4 text-[18px] lg:text-[20px] text-black/90 leading-relaxed">
                        <p>“Hiền từng giống như rất nhiều chị em khác – mua mỹ phẩm theo cảm tính, tự học makeup trên mạng và nghĩ rằng như vậy là đủ…”</p>
                        <p>Năm 2018, vì đặc thù công việc, Hiền phải trang điểm thường xuyên. Makeup ban đầu trông ổn, nhưng chỉ sau vài tiếng: nền mốc, lớp makeup loang lổ dưới ánh nắng, thiếu kiến thức xử lý da và sản phẩm.</p>
                        <p>Những dịp quan trọng, Hiền phải thuê thợ makeup với chi phí 500.000 – 800.000đ/lần. Ngày lễ, ngày Tết thì không đặt được lịch, hoặc bị bump lịch phút chót.</p>
                        <p><strong>Chính những trải nghiệm đó khiến Hiền nhận ra:</strong><br>Makeup không chỉ là đẹp – mà cần đúng kỹ thuật và hiểu bản chất.</p>
                        <p><strong>Makeup không chỉ là đẹp – mà cần đúng kỹ thuật và hiểu bản chất.</strong></p>
                    </div>
                </div>

                <div class="w-[40%] lg:w-1/2 flex justify-end lg:block">
                    <img
                        src="/storage/hien.webp"
                        alt="Hiền Mew"
                        class="max-w-[480px] w-full h-auto object-contain lg:absolute lg:bottom-0 lg:right-0"
                    >
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#ffffff] pb-14 md:pb-16">
        <div class="max-w-[1536px] mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
                <div class="rounded-sm overflow-hidden">
                    <img
                        src="/storage/mew-studio.webp"
                        alt="Mewart studio"
                        class="w-full h-full object-cover"
                    >
                </div>

                <div>
                    <p class="text-[14px] md:text-[24px] text-black/90 leading-relaxed mb-6">
                        Thời điểm Hiền bắt đầu học makeup, việc đào tạo còn thiếu bài bản, kiến thức rời rạc, dễ hỏng nền tảng. Vì vậy, Hiền quyết định xây dựng Mew Art Makeup Academy – một hệ thống đào tạo.
                    </p>

                    <ul class="space-y-4 text-[24px] md:text-[34px] text-black heading-font">
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full border border-black inline-flex items-center justify-center text-[14px]">✓</span>
                            <span>Có giáo trình rõ ràng</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full border border-black inline-flex items-center justify-center text-[14px]">✓</span>
                            <span>Có tiêu chuẩn chuyên môn</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full border border-black inline-flex items-center justify-center text-[14px]">✓</span>
                            <span>Có trách nhiệm với học viên và nghề</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
