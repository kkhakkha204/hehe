<div class="course-grid-root grid grid-cols-1 items-stretch gap-4 md:grid-cols-2 xl:grid-cols-3" data-total="{{ $courses->total() }}">
    @forelse ($courses as $course)
        <x-f8-course-card :course="$course" compact />
    @empty
        <div class="course-grid-empty col-span-full rounded-[24px] border border-[#eaecf0] bg-[#f8fafc] px-6 py-14 text-center">
            <h3 class="text-[24px] font-bold tracking-[-0.02em] text-[#111827] md:text-[28px]">Chưa có khóa học nào</h3>
            <p class="mt-3 text-[15px] leading-7 text-[#667085]">
                Dữ liệu khóa học sẽ xuất hiện ở đây ngay khi hệ thống có nội dung phù hợp với bộ lọc hiện tại.
            </p>
        </div>
    @endforelse
</div>
