@extends('layouts.app-public')

@section('title', 'Thanh toán - ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Thanh toán khóa học</h1>
                <p class="mt-2 text-gray-600">Quét mã QR để hoàn tất thanh toán</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Cột trái: QR Code -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">

                    <!-- Countdown Timer -->
                    <div class="text-center mb-6" x-data="countdown('{{ $order->expires_at->toIso8601String() }}')">
                        <div class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Còn lại: <span x-text="timeLeft"></span></span>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="flex justify-center mb-6">
                        <div class="bg-white p-4 rounded-lg border-4 border-gray-900">
                            <img
                                src="https://img.vietqr.io/image/{{ config('sepay.bank_id', 'MB') }}-{{ config('sepay.account_number') }}-compact2.png?amount={{ $order->final_amount }}&addInfo={{ $order->order_code }}&accountName={{ config('sepay.account_name') }}"
                                alt="QR Code"
                                class="w-64 h-64"
                            >
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngân hàng:</span>
                            <span class="font-semibold">{{ config('sepay.bank_name', 'MB Bank') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Số tài khoản:</span>
                            <span class="font-semibold">{{ config('sepay.account_number') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Chủ tài khoản:</span>
                            <span class="font-semibold">{{ config('sepay.account_name') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Số tiền:</span>
                            <span class="font-semibold text-red-600 text-lg">{{ number_format($order->final_amount) }}₫</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Nội dung CK:</span>
                            <div class="flex items-center space-x-2">
                                <span class="font-semibold text-blue-600">{{ $order->order_code }}</span>
                                <button
                                    onclick="copyOrderCode('{{ $order->order_code }}')"
                                    class="p-1 hover:bg-gray-100 rounded"
                                    title="Copy"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            <strong>Lưu ý:</strong> Vui lòng chuyển khoản đúng số tiền và đúng nội dung để hệ thống tự động kích hoạt khóa học.
                        </p>
                    </div>

                    <!-- Auto Check Status -->
                    <div class="mt-6 text-center" x-data="paymentChecker({{ $order->id }})">
                        <div x-show="checking" class="flex items-center justify-center text-gray-600">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-black mr-2"></div>
                            <span class="text-sm">Đang kiểm tra thanh toán...</span>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Thông tin khóa học -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold mb-4">Thông tin đơn hàng</h2>

                    <div class="flex space-x-4 pb-6 border-b">
                        <img
                            src="{{ $course->thumbnail_url }}"
                            alt="{{ $course->title }}"
                            class="w-32 h-20 object-cover rounded"
                        >
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $course->category->name }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giá gốc:</span>
                            <span class="font-semibold">{{ number_format($order->amount) }}₫</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="text-red-600 font-semibold">-{{ number_format($order->discount_amount) }}₫</span>
                            </div>

                            @if($order->coupon)
                                <div class="p-3 bg-green-50 border border-green-200 rounded">
                                    <p class="text-xs text-green-800">
                                        <strong>Mã:</strong> {{ $order->coupon->code }}
                                    </p>
                                </div>
                            @endif
                        @endif

                        <div class="pt-3 border-t">
                            <div class="flex justify-between">
                                <span class="font-bold text-lg">Tổng cộng:</span>
                                <span class="font-bold text-2xl text-gray-900">{{ number_format($order->final_amount) }}₫</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <ul class="space-y-2 text-sm text-green-800">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Truy cập trọn đời
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $course->total_lessons }} bài học
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Hỗ trợ 24/7
                            </li>
                        </ul>
                    </div>

                    <a
                        href="{{ route('courses.show', $course->slug) }}"
                        class="mt-6 block text-center py-2 text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Quay lại trang khóa học
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Copy order code
        function copyOrderCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Đã copy mã đơn hàng: ' + code);
            });
        }

        // Countdown timer
        function countdown(expiresAt) {
            return {
                timeLeft: '',
                interval: null,

                init() {
                    this.updateTime();
                    this.interval = setInterval(() => {
                        this.updateTime();
                    }, 1000);
                },

                updateTime() {
                    const now = new Date().getTime();
                    const expiry = new Date(expiresAt).getTime();
                    const distance = expiry - now;

                    if (distance < 0) {
                        this.timeLeft = 'Đã hết hạn';
                        clearInterval(this.interval);
                        setTimeout(() => {
                            window.location.href = '{{ route('courses.show', $course->slug) }}';
                        }, 3000);
                        return;
                    }

                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    this.timeLeft = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
            }
        }

        // Auto check payment status
        function paymentChecker(orderId) {
            return {
                checking: false,
                interval: null,

                init() {
                    this.startChecking();
                },

                startChecking() {
                    this.checking = true;
                    this.interval = setInterval(() => {
                        this.checkStatus();
                    }, 5000); // Check mỗi 5 giây
                },

                async checkStatus() {
                    try {
                        const response = await fetch(`/orders/${orderId}/status`);
                        const data = await response.json();

                        if (data.is_paid) {
                            clearInterval(this.interval);
                            this.showSuccess();
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 2000);
                        }
                    } catch (error) {
                        console.error('Check status error:', error);
                    }
                },

                showSuccess() {
                    alert('✅ Thanh toán thành công! Đang chuyển hướng...');
                }
            }
        }
    </script>
@endpush
