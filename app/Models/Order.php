<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_code',
        'amount',
        'discount_amount', // Thêm
        'final_amount', // Thêm
        'coupon_id', // Thêm
        'status',
        'bank_transaction_id',
        'paid_at',
        'expires_at',
        'payment_data',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:0', // Thêm
        'final_amount' => 'decimal:0',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Generate unique order code
    public static function generateOrderCode(): string
    {
        do {
            $code = 'SE' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
