<?php

namespace App\Filament\Resources\WorkoutIntents\Pages;

use App\Filament\Resources\WorkoutIntents\WorkoutIntentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutIntent extends EditRecord
{
    protected static string $resource = WorkoutIntentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make()
        ];
    }
}
