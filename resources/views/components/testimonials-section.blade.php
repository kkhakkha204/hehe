<section class="testimonials-section relative z-10">
    <!-- Split Background: Top Black, Bottom White -->
    <div class="absolute inset-x-0 top-0 h-[40%] md:h-[50%] bg-[#000000] -z-10"></div>
    <div class="absolute inset-x-0 bottom-0 h-[60%] md:h-[50%] bg-[#ffffff] -z-10"></div>

    <div class="container max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        <!-- Feature Card -->
        <div
            class="card-container bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.1)] overflow-visible relative flex flex-col lg:flex-row lg:items-stretch border border-gray-100 bg-cover bg-center bg-no-repeat lg:h-[448px]"
            style="background-image: url('{{ asset('storage/nen.webp') }}');"
        >
            
            <!-- Left Content -->
            <div class="w-full lg:flex-1 p-8 md:p-10 lg:p-20 z-10 flex flex-col justify-center">
                <h2 class="heading-font text-[30px] md:text-[36px] font-normal text-black uppercase mb-6 md:mb-8 leading-none">
                    Cảm nhận học viên
                </h2>
                
                <div class="space-y-5 md:space-y-6 text-gray-600 text-[15px] md:text-base leading-relaxed mb-8 md:mb-10 pr-0 lg:pr-8">
                    <p>
                        Học viên cho biết họ rất yên tâm khi được học cùng đội ngũ hơn 11 giảng viên giàu kinh nghiệm, trong đó có các giảng viên cấp cao từng được đào tạo tại những học viện makeup quốc tế.
                    </p>
                    <p>
                        Nhiều học viên đánh giá cao việc được học từ giảng viên có kinh nghiệm thực tế lâu năm, đã làm việc với nghệ sĩ, KOL, MC trong các sự kiện lớn và đào tạo cho hàng nghìn học viên.
                    </p>
                </div>

                <div>
                    <a href="#" class="inline-block border-2 border-black text-black bg-transparent hover:bg-black hover:text-white transition-colors duration-300 font-bold text-sm px-10 py-3.5 uppercase tracking-wider">
                        Xem ngay
                    </a>
                </div>
            </div>

            <!-- Right Image -->
            <div class="w-full lg:w-[452px] lg:flex-none relative z-10 flex justify-center lg:justify-end items-end h-full mt-4 lg:mt-0">
                <img 
                    src="{{ asset('storage/camnhan.webp') }}" 
                    alt="Cảm nhận học viên" 
                    class="w-[180px] max-w-[180px] md:w-[260px] md:max-w-[260px] lg:w-[452px] lg:max-w-none h-auto object-contain rounded-br-[2rem] lg:rounded-bl-none lg:-mt-6"
                    onerror="this.src='https://ui-avatars.com/api/?name=Hoc+Vien&size=500&background=f3f4f6&color=333';"
                >
            </div>
        </div>
    </div>
</section>