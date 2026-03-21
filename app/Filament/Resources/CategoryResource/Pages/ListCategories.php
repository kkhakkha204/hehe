<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListCategories extends SinglePageListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('3xl')
                ->label('Tạo danh mục mới'),
        ];
    }
}
