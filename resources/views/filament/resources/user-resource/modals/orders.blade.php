<div class="space-y-4">
    @forelse($user->orders()->latest()->get() as $order)
        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-mono font-semibold text-sm text-gray-900">{{ $order->order_code }}</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $order->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}
                    ">
                        {{ ucfirst($order->status) }}
                    </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $order->course->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
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
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p>No orders yet</p>
        </div>
    @endforelse
</div>
