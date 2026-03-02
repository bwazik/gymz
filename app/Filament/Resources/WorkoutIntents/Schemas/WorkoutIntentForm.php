<?php

namespace App\Filament\Resources\WorkoutIntents\Schemas;

use App\Enums\IntentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkoutIntentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('gym_id')
                    ->relationship('gym', 'name')
                    ->required(),
                Select::make('workout_target_id')
                    ->relationship('workoutTarget', 'name')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                Toggle::make('has_invitation')
                    ->required(),
                Textarea::make('note')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(IntentStatus::class)
                    ->required()
                    ->default(0),
            ]);
    }
}
