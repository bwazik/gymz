<?php

namespace App\Filament\Resources\WorkoutTargets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkoutTargetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workout_category_id')
                    ->relationship('workoutCategory', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
