<?php

namespace App\Filament\Resources\WorkoutCategories\Pages;

use App\Filament\Resources\WorkoutCategories\WorkoutCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutCategories extends ListRecords
{
    protected static string $resource = WorkoutCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
