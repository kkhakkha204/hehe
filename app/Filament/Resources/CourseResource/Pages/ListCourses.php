<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Filament\Resources\Pages\SinglePageListRecords;
use Filament\Actions;

class ListCourses extends SinglePageListRecords
{
    protected static string $resource = CourseResource::class;

    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo khóa học mới'),
        ];
    }
}
