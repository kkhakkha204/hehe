<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $students = User::where('role', 'student')->count();

        $newUsersToday = User::whereDate('created_at', today())->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Tổng người dùng', number_format($totalUsers))
                ->description('Tất cả tài khoản đã đăng ký')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart($this->getUserGrowthChart(7)),

            Stat::make('Quản trị viên', number_format($admins))
                ->description('Tài khoản có quyền quản trị')
                ->descriptionIcon('heroicon-o-shield-check')
                ->color('danger'),

            Stat::make('Học viên', number_format($students))
                ->description('Người dùng đang học')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),

            Stat::make('Mới hôm nay', number_format($newUsersToday))
                ->description($newUsersThisMonth . ' tài khoản trong tháng này')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning'),
        ];
    }

    private function getUserGrowthChart(int $days): array
    {
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $data[] = $count;
        }

        return $data;
    }
}
