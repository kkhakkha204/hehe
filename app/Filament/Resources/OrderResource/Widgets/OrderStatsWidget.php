<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Today
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('final_amount');

        $todayOrders = Order::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->count();

        // This Month
        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('final_amount');

        $monthOrders = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->count();

        // All Time
        $totalRevenue = Order::where('status', 'paid')->sum('final_amount');
        $totalOrders = Order::where('status', 'paid')->count();

        // Pending
        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingAmount = Order::where('status', 'pending')->sum('final_amount');

        return [
            Stat::make('Today Revenue', number_format($todayRevenue) . '₫')
                ->description($todayOrders . ' orders')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('success')
                ->chart($this->getRevenueChart(7)), // 7 ngày gần nhất

            Stat::make('This Month', number_format($monthRevenue) . '₫')
                ->description($monthOrders . ' orders')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Total Revenue', number_format($totalRevenue) . '₫')
                ->description($totalOrders . ' orders all time')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description(number_format($pendingAmount) . '₫ pending')
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
