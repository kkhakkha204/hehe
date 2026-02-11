<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Không cho tạo order thủ công
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderResource\Widgets\OrderStatsWidget::class,
            OrderResource\Widgets\OrderRevenueChart::class,
        ];
    }
}
