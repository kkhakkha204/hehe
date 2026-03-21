<div class="space-y-4">
    @forelse($user->orders()->latest()->get() as $order)
        <div class="rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-mono text-sm font-semibold text-gray-900">{{ $order->order_code }}</span>
                        <span class="rounded-full px-2 py-1 text-xs font-medium
                        {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $order->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ match($order->status) {
                                'paid' => 'Đã thanh toán',
                                'pending' => 'Chờ thanh toán',
                                'cancelled' => 'Đã hủy',
                                'expired' => 'Hết hạn',
                                default => $order->status,
                            } }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ $order->course->title }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-gray-900">{{ number_format($order->final_amount) }}₫</p>
                    @if($order->discount_amount > 0)
                        <p class="text-xs text-gray-500 line-through">{{ number_format($order->amount) }}₫</p>
                        <p class="text-xs text-green-600">-{{ number_format($order->discount_amount) }}₫</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="py-8 text-center text-gray-500">
            <svg class="mx-auto mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p>Chưa có đơn hàng nào</p>
        </div>
    @endforelse
</div>
