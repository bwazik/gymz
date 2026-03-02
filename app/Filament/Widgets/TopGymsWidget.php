<?php

namespace App\Filament\Widgets;

use App\Models\Gym;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopGymsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Active Gyms';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Gym::withCount('workoutIntents')->orderByDesc('workout_intents_count')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('workout_intents_count')
                    ->label('Intents'),
            ])
            ->paginated(false);
    }
}
