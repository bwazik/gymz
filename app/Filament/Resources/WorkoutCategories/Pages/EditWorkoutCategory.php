<?php

namespace App\Filament\Resources\WorkoutCategories\Pages;

use App\Filament\Resources\WorkoutCategories\WorkoutCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutCategory extends EditRecord
{
    protected static string $resource = WorkoutCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
