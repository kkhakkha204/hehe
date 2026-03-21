<?php

namespace App\Filament\Resources\CouponResource\Widgets;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CouponStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        $totalUsages = CouponUsage::count();
        $totalDiscountGiven = CouponUsage::sum('discount_amount');

        return [
            Stat::make('Tổng mã giảm giá', $totalCoupons)
                ->description('Toàn thời gian')
                ->descriptionIcon('heroicon-o-ticket')
                ->color('primary'),

            Stat::make('Mã đang hoạt động', $activeCoupons)
                ->description('Hiện đang áp dụng')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tổng lượt sử dụng', number_format($totalUsages))
                ->description('Số lần mã đã được dùng')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Tổng tiền đã giảm', number_format($totalDiscountGiven) . '₫')
                ->description('Tổng ưu đãi đã áp dụng cho khách hàng')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}
