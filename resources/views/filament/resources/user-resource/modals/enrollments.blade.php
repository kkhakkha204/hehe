<div class="space-y-4">
    @forelse($user->enrollments as $enrollment)
        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
            <div class="flex items-center space-x-4">
                <img
                    src="{{ $enrollment->course->thumbnail_url }}"
                    alt="{{ $enrollment->course->title }}"
                    class="w-20 h-14 object-cover rounded"
                >
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">{{ $enrollment->course->title }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $enrollment->course->category->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Enrolled: {{ $enrollment->enrolled_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-gray-900">{{ number_format($enrollment->order->final_amount ?? 0) }}₫</p>
                    @if($enrollment->order && $enrollment->order->discount_amount > 0)
                        <p class="text-xs text-green-600">Saved: {{ number_format($enrollment->order->discount_amount) }}₫</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <p>No enrolled courses yet</p>
        </div>
    @endforelse
</div>
