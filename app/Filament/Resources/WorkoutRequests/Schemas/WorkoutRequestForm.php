<?php

namespace App\Filament\Resources\WorkoutRequests\Schemas;

use App\Enums\RequestStatus;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WorkoutRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('intent_id')
                    ->label(__('نية التمرين'))
                    ->relationship('workoutIntent', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('sender_id')
                    ->label(__('المرسل'))
                    ->relationship('sender', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label(__('الحالة'))
                    ->options(RequestStatus::class)
                    ->required()
                    ->default(RequestStatus::PENDING),
            ]);
    }
}
