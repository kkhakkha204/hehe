<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PreCheckoutController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Trang Pre-Checkout
     */
    public function show(Course $course)
    {
        $user = auth()->user();

        // Check đã mua chưa
        if ($user->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn đã sở hữu khóa học này.');
        }

        // Nếu khóa học FREE → Tự động enroll luôn
        if ($course->isFree()) {
            $this->autoEnrollFreeCourse($user, $course);

            return redirect()->route('learning.show', $course->slug)
                ->with('success', 'Chúc mừng! Bạn đã được ghi danh vào khóa học miễn phí.');
        }

        return view('pre-checkout.show', compact('course', 'user'));
    }

    /**
     * Tự động enroll khóa học miễn phí
     */
    private function autoEnrollFreeCourse(User $user, Course $course): void
    {
        // Tạo order với status = paid (không cần thanh toán)
        $order = Order::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_code' => Order::generateOrderCode(),
            'amount' => 0,
            'discount_amount' => 0,
            'final_amount' => 0,
            'coupon_id' => null,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Tạo enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_id' => $order->id,
            'enrolled_at' => now(),
        ]);

        // Tăng số lượng học viên
        $course->increment('current_students');
    }

    /**
     * Validate coupon (AJAX)
     */
    public function validateCoupon(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->active()
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
            ], 422);
        }

        if (!$coupon->canBeUsedBy(auth()->user(), $course)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể sử dụng mã giảm giá này.'
            ], 422);
        }

        $originalPrice = $course->display_price;
        $discountAmount = $coupon->calculateDiscount($originalPrice);
        $finalPrice = $originalPrice - $discountAmount;

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'data' => [
                'coupon_id' => $coupon->id,
                'original_price' => $originalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'discount_text' => $coupon->type === 'percentage'
                    ? "Giảm {$coupon->value}%"
                    : "Giảm " . number_format($coupon->value) . "₫"
            ]
        ]);
    }

    /**
     * Update phone (AJAX)
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{10}$/'
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải có 10 chữ số.'
        ]);

        auth()->user()->update([
            'phone' => $request->phone
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật số điện thoại thành công!'
        ]);
    }
}
