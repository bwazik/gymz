<?php

namespace App\Filament\Resources\WorkoutCategories\Pages;

use App\Filament\Resources\WorkoutCategories\WorkoutCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkoutCategory extends CreateRecord
{
    protected static string $resource = WorkoutCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
