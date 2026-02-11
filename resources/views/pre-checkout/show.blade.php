@extends('layouts.app-public')

@section('title', 'Xác nhận đơn hàng - ' . $course->title)

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('courses.show', $course->slug) }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Xác nhận đơn hàng</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="preCheckout()">

                <!-- Cột trái: Form -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Thông tin cá nhân -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold mb-4">Thông tin liên hệ</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Họ và tên
                                </label>
                                <input
                                    type="text"
                                    value="{{ $user->name }}"
                                    disabled
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value="{{ $user->email }}"
                                    disabled
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Số điện thoại <span class="text-red-600">*</span>
                                </label>
                                <input
                                    type="tel"
                                    x-model="phone"
                                    :disabled="hasPhone"
                                    :class="hasPhone ? 'bg-gray-50 text-gray-600' : 'bg-white'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    placeholder="Nhập số điện thoại (10 số)"
                                    maxlength="10"
                                >
                                <p x-show="phoneError" x-text="phoneError" class="text-sm text-red-600 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mã giảm giá -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold mb-4">Mã giảm giá</h2>

                        <div class="flex gap-3">
                            <input
                                type="text"
                                x-model="couponCode"
                                @keyup.enter="applyCoupon()"
                                :disabled="couponApplied"
                                placeholder="Nhập mã giảm giá"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent uppercase"
                            >

                            <button
                                @click="couponApplied ? removeCoupon() : applyCoupon()"
                                :disabled="!couponCode && !couponApplied"
                                :class="couponApplied ? 'bg-red-600 hover:bg-red-700' : 'bg-black hover:bg-gray-800'"
                                class="px-6 py-3 text-white rounded-lg font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed"
                                x-text="couponApplied ? 'Xóa' : 'Áp dụng'"
                            >
                            </button>
                        </div>

                        <div x-show="couponMessage" class="mt-3">
                            <p :class="couponSuccess ? 'text-green-600' : 'text-red-600'" class="text-sm" x-text="couponMessage"></p>
                        </div>

                        <div x-show="couponApplied" x-transition class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-800 font-medium" x-text="couponData.discount_text"></span>
                                <span class="text-lg font-bold text-green-600">
                                -<span x-text="formatMoney(couponData.discount_amount)"></span>₫
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Tóm tắt đơn hàng -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-bold mb-4">Tóm tắt đơn hàng</h2>

                        <!-- Course Info -->
                        <div class="flex space-x-3 pb-4 border-b mb-4">
                            <img
                                src="{{ $course->thumbnail_url }}"
                                alt="{{ $course->title }}"
                                class="w-20 h-14 object-cover rounded"
                            >
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-sm text-gray-900 line-clamp-2">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-xs text-gray-600 mt-1">{{ $course->category->name }}</p>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giá gốc:</span>
                                <span class="font-semibold" x-text="formatMoney(originalPrice) + '₫'"></span>
                            </div>

                            <div x-show="couponApplied" class="flex justify-between text-sm">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-semibold text-red-600">-<span x-text="formatMoney(couponData.discount_amount)"></span>₫</span>
                            </div>

                            <div class="pt-3 border-t">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-bold text-lg">Tổng cộng:</span>
                                    <span class="font-bold text-2xl text-gray-900" x-text="formatMoney(finalPrice) + '₫'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            @click="proceedToCheckout()"
                            :disabled="processing || (!hasPhone && !phone)"
                            class="w-full bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
    <span x-show="!processing">
        @if($course->isFree())
            Ghi danh miễn phí
        @else
            Thanh toán
        @endif
    </span>
                            <span x-show="processing" class="flex items-center justify-center">
        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Đang xử lý...
    </span>
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            Bằng việc thanh toán, bạn đồng ý với <a href="#" class="underline">Điều khoản dịch vụ</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function preCheckout() {
            return {
                // Data
                originalPrice: {{ $course->display_price }},
                finalPrice: {{ $course->display_price }},
                phone: '{{ $user->phone ?? "" }}',
                hasPhone: {{ $user->phone ? 'true' : 'false' }},
                phoneError: '',

                couponCode: '',
                couponApplied: false,
                couponData: {},
                couponMessage: '',
                couponSuccess: false,

                processing: false,

                // Methods
                formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN').format(amount);
                },

                async applyCoupon() {
                    if (!this.couponCode) return;

                    this.processing = true;
                    this.couponMessage = '';

                    try {
                        const response = await fetch('{{ route("pre-checkout.validate-coupon", $course->slug) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ code: this.couponCode })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.couponApplied = true;
                            this.couponData = data.data;
                            this.finalPrice = data.data.final_price;
                            this.couponMessage = data.message;
                            this.couponSuccess = true;
                        } else {
                            this.couponMessage = data.message;
                            this.couponSuccess = false;
                        }
                    } catch (error) {
                        this.couponMessage = 'Có lỗi xảy ra. Vui lòng thử lại.';
                        this.couponSuccess = false;
                    }

                    this.processing = false;
                },

                removeCoupon() {
                    this.couponApplied = false;
                    this.couponData = {};
                    this.couponMessage = '';
                    this.finalPrice = this.originalPrice;
                    this.couponCode = '';
                },

                async updatePhone() {
                    if (!this.phone || this.hasPhone) return true;

                    // Validate phone
                    if (!/^[0-9]{10}$/.test(this.phone)) {
                        this.phoneError = 'Số điện thoại phải có 10 chữ số.';
                        return false;
                    }

                    try {
                        const response = await fetch('{{ route("pre-checkout.update-phone") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ phone: this.phone })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.hasPhone = true;
                            this.phoneError = '';
                            return true;
                        } else {
                            this.phoneError = data.message || 'Có lỗi xảy ra.';
                            return false;
                        }
                    } catch (error) {
                        this.phoneError = 'Có lỗi xảy ra. Vui lòng thử lại.';
                        return false;
                    }
                },

                async proceedToCheckout() {
                    this.processing = true;
                    this.phoneError = '';

                    // Update phone nếu chưa có
                    const phoneUpdated = await this.updatePhone();

                    if (!phoneUpdated) {
                        this.processing = false;
                        return;
                    }

                    // ✅ Nếu giá cuối = 0 (sau khi dùng coupon hoặc khóa FREE)
                    // → Redirect trực tiếp, backend sẽ tự động enroll
                    let url = '{{ route("checkout.show", $course->slug) }}';
                    if (this.couponApplied && this.couponData.coupon_id) {
                        url += '?coupon_id=' + this.couponData.coupon_id;
                    }

                    window.location.href = url;
                }
            }
        }
    </script>
@endpush
