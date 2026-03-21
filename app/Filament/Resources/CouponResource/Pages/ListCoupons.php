<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListCoupons extends SinglePageListRecords
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('5xl')
                ->label('Tạo mã giảm giá'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
