<?php

namespace App\Filament\Resources\WorkoutTargets\Pages;

use App\Filament\Resources\WorkoutTargets\WorkoutTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutTargets extends ListRecords
{
    protected static string $resource = WorkoutTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
