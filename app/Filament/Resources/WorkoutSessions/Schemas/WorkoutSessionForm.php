<?php

namespace App\Filament\Resources\WorkoutSessions\Schemas;

use App\Enums\SessionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkoutSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('intent_id')
                    ->required()
                    ->numeric(),
                Select::make('user_a_id')
                    ->relationship('userA', 'name')
                    ->required(),
                Select::make('user_b_id')
                    ->relationship('userB', 'name')
                    ->required(),
                DateTimePicker::make('scanned_at'),
                Select::make('status')
                    ->options(SessionStatus::class)
                    ->required()
                    ->default(0),
            ]);
    }
}
