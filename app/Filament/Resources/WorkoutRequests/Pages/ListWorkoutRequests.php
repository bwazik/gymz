<?php

namespace App\Filament\Resources\WorkoutRequests\Pages;

use App\Filament\Resources\WorkoutRequests\WorkoutRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutRequests extends ListRecords
{
    protected static string $resource = WorkoutRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
