<?php

namespace App\Filament\Widgets;

use App\Models\Gym;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopGymsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    protected static ?string $heading = 'الجيمات الأكثر تفاعلا';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Gym::withCount('workoutIntents')->orderByDesc('workout_intents_count')->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('الإسم'),
                TextColumn::make('city.name')
                    ->label('المدينة'),
                TextColumn::make('workout_intents_count')
                    ->label('عدد الطلبات'),
            ])
            ->paginated(false);
    }
}
