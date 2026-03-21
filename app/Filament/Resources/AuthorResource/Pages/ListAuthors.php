<?php

namespace App\Filament\Resources\AuthorResource\Pages;

use App\Filament\Resources\AuthorResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListAuthors extends SinglePageListRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('4xl')
                ->label('Thêm giảng viên'),
        ];
    }
}
