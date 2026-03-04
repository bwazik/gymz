<?php

namespace App\Filament\Resources\WorkoutIntents\Pages;

use App\Filament\Resources\WorkoutIntents\WorkoutIntentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkoutIntent extends CreateRecord
{
    protected static string $resource = WorkoutIntentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
