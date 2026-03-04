<?php

namespace App\Filament\Resources\WorkoutIntents;

use App\Filament\Resources\WorkoutIntents\Pages\ListWorkoutIntents;
use App\Filament\Resources\WorkoutIntents\Schemas\WorkoutIntentForm;
use App\Filament\Resources\WorkoutIntents\Tables\WorkoutIntentsTable;
use App\Models\WorkoutIntent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkoutIntentResource extends Resource
{
    protected static ?string $model = WorkoutIntent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'نية التمرين';
    }

    public static function getPluralModelLabel(): string
    {
        return 'نوايا التمارين (Intents)';
    }

    public static function form(Schema $schema): Schema
    {
        return WorkoutIntentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutIntentsTable::configure($table);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkoutIntents::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
