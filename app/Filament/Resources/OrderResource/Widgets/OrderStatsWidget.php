<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('final_amount');

        $todayOrders = Order::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->count();

        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('final_amount');

        $monthOrders = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->count();

        $totalRevenue = Order::where('status', 'paid')->sum('final_amount');
        $totalOrders = Order::where('status', 'paid')->count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingAmount = Order::where('status', 'pending')->sum('final_amount');

        return [
            Stat::make('Doanh thu hôm nay', number_format($todayRevenue) . '₫')
                ->description($todayOrders . ' đơn hàng')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('success')
                ->chart($this->getRevenueChart(7)),

            Stat::make('Tháng này', number_format($monthRevenue) . '₫')
                ->description($monthOrders . ' đơn hàng')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Tổng doanh thu', number_format($totalRevenue) . '₫')
                ->description($totalOrders . ' đơn hàng toàn thời gian')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make('Đơn chờ thanh toán', $pendingOrders)
                ->description(number_format($pendingAmount) . '₫ đang chờ')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    private function getRevenueChart(int $days): array
    {
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::where('status', 'paid')
                ->whereDate('paid_at', $date)
                ->sum('final_amount');
            $data[] = $revenue;
        }

        return $data;
    }
}
