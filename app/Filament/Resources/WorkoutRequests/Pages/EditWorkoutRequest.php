<?php

namespace App\Filament\Resources\WorkoutRequests\Pages;

use App\Filament\Resources\WorkoutRequests\WorkoutRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutRequest extends EditRecord
{
    protected static string $resource = WorkoutRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
