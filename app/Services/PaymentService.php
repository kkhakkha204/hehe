<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Order;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Tạo đơn hàng mới
     */
    public function createOrder(User $user, Course $course, ?Coupon $coupon = null): Order
    {
        // Check đã mua chưa
        if ($user->hasEnrolled($course->id)) {
            throw new \Exception('Bạn đã sở hữu khóa học này.');
        }

        // Check đã có order pending chưa
        $existingOrder = Order::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingOrder) {
            return $existingOrder;
        }

        // Tính giá
        $originalPrice = $course->display_price;
        $discountAmount = 0;

        if ($coupon) {
            // Validate coupon
            if (!$coupon->canBeUsedBy($user, $course)) {
                throw new \Exception('Mã giảm giá không hợp lệ.');
            }

            $discountAmount = $coupon->calculateDiscount($originalPrice);
        }

        $finalAmount = $originalPrice - $discountAmount;

        // Tạo order mới
        return Order::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_code' => Order::generateOrderCode(),
            'amount' => $originalPrice,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'coupon_id' => $coupon ? $coupon->id : null,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);
    }

    /**
     * Xử lý khi nhận được webhook từ SePay
     */
    public function processPayment(string $orderCode, array $webhookData): bool
    {
        return DB::transaction(function () use ($orderCode, $webhookData) {
            // Tìm order
            $order = Order::where('order_code', $orderCode)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (!$order) {
                \Log::warning('Order not found or already processed', [
                    'order_code' => $orderCode,
                    'webhook_data' => $webhookData
                ]);
                return false;
            }

            // Kiểm tra số tiền (phải bằng final_amount)
            $receivedAmount = (int) $webhookData['amount'];
            if ($receivedAmount < $order->final_amount) {
                \Log::warning('Payment amount mismatch', [
                    'order' => $order->toArray(),
                    'received' => $receivedAmount,
                    'expected' => $order->final_amount
                ]);
                return false;
            }

            // Cập nhật order
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'bank_transaction_id' => $webhookData['transaction_id'] ?? null,
                'payment_data' => $webhookData,
            ]);

            // Tạo enrollment
            Enrollment::create([
                'user_id' => $order->user_id,
                'course_id' => $order->course_id,
                'order_id' => $order->id,
                'enrolled_at' => now(),
            ]);

            // Tăng số lượng học viên
            $order->course->increment('current_students');

            // Nếu có dùng coupon → Tạo usage và tăng usage_count
            if ($order->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount_amount,
                ]);

                $order->coupon->increment('usage_count');
            }

            \Log::info('✅ Payment processed successfully', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'course_id' => $order->course_id,
                'final_amount' => $order->final_amount,
                'discount_amount' => $order->discount_amount,
            ]);

            return true;
        });
    }

    /**
     * Hủy order hết hạn
     */
    public function cancelExpiredOrders(): int
    {
        return Order::pending()
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
    }

    /**
     * Xử lý khóa học FREE sau khi dùng coupon 100%
     */
    public function processFreeCourseWithCoupon(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // Cập nhật order thành paid
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Tạo enrollment
            Enrollment::create([
                'user_id' => $order->user_id,
                'course_id' => $order->course_id,
                'order_id' => $order->id,
                'enrolled_at' => now(),
            ]);

            // Tăng số lượng học viên
            $order->course->increment('current_students');

            // Tạo coupon usage và tăng usage_count
            if ($order->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount_amount,
                ]);

                $order->coupon->increment('usage_count');
            }

            \Log::info('✅ Free course enrolled with coupon', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'course_id' => $order->course_id,
            ]);
        });
    }
}
