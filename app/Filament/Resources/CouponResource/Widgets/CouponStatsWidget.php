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
            Stat::make('Total Coupons', $totalCoupons)
                ->description('All time')
                ->descriptionIcon('heroicon-o-ticket')
                ->color('primary'),

            Stat::make('Active Coupons', $activeCoupons)
                ->description('Currently active')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Uses', number_format($totalUsages))
                ->description('Times coupons were used')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Total Discount Given', number_format($totalDiscountGiven) . '₫')
                ->description('Total savings for customers')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}
