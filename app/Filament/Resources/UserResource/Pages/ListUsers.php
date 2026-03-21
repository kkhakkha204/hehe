<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListUsers extends SinglePageListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('5xl')
                ->label('Tạo người dùng'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
