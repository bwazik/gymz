<?php

namespace App\Filament\Resources\WorkoutTargets\Pages;

use App\Filament\Resources\WorkoutTargets\WorkoutTargetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkoutTarget extends CreateRecord
{
    protected static string $resource = WorkoutTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
