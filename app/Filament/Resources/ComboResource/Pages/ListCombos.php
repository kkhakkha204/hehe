<?php

namespace App\Filament\Resources\ComboResource\Pages;

use App\Filament\Resources\ComboResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListCombos extends SinglePageListRecords
{
    protected static string $resource = ComboResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('5xl')
                ->label('Thêm combo'),
        ];
    }
}
