<?php

namespace App\Filament\Resources\WorkoutIntents\Pages;

use App\Filament\Resources\WorkoutIntents\WorkoutIntentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutIntents extends ListRecords
{
    protected static string $resource = WorkoutIntentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
