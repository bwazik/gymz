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
                TextInput::make('name')
                    ->label(__('الاسم'))
                    ->required()
                    ->maxLength(255),
                Select::make('workout_category_id')
                    ->label(__('فئة التمرين'))
                    ->relationship('workoutCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
