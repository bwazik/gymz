<?php

namespace App\Filament\Resources\WorkoutTargets\Pages;

use App\Filament\Resources\WorkoutTargets\WorkoutTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutTarget extends EditRecord
{
    protected static string $resource = WorkoutTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
