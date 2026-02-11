<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Coupon;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CheckoutController extends Controller implements HasMiddleware
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Trang checkout (QR Code)
     */
    public function show(Request $request, Course $course)
    {
        // Validate request
        $request->validate([
            'coupon_id' => 'nullable|exists:coupons,id'
        ]);

        // Check phone
        if (!auth()->user()->phone) {
            return redirect()->route('pre-checkout.show', $course->slug)
                ->with('error', 'Vui lòng nhập số điện thoại trước khi thanh toán.');
        }

        // Check đã mua chưa
        if (auth()->user()->hasEnrolled($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Bạn đã sở hữu khóa học này.');
        }

        // Tạo order
        try {
            $coupon = null;
            if ($request->coupon_id) {
                $coupon = Coupon::find($request->coupon_id);
            }

            $order = $this->paymentService->createOrder(auth()->user(), $course, $coupon);

            // ✅ NẾU GIÁ = 0 SAU KHI DÙNG COUPON → Tự động paid
            if ($order->final_amount == 0) {
                $this->paymentService->processFreeCourseWithCoupon($order);

                return redirect()->route('learning.show', $course->slug)
                    ->with('success', 'Chúc mừng! Bạn đã nhận được khóa học miễn phí nhờ mã giảm giá.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('checkout.show', compact('order', 'course'));
    }

    /**
     * Check trạng thái thanh toán (AJAX)
     */
    public function checkStatus(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json([
            'status' => $order->status,
            'is_paid' => $order->isPaid(),
            'redirect_url' => $order->isPaid()
                ? route('courses.show', $order->course->slug)
                : null
        ]);
    }
}
