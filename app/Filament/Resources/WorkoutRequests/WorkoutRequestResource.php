<?php

namespace App\Filament\Resources\WorkoutRequests;

use App\Filament\Resources\WorkoutRequests\Pages\CreateWorkoutRequest;
use App\Filament\Resources\WorkoutRequests\Pages\EditWorkoutRequest;
use App\Filament\Resources\WorkoutRequests\Pages\ListWorkoutRequests;
use App\Filament\Resources\WorkoutRequests\Schemas\WorkoutRequestForm;
use App\Filament\Resources\WorkoutRequests\Tables\WorkoutRequestsTable;
use App\Models\WorkoutRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkoutRequestResource extends Resource
{
    protected static ?string $model = WorkoutRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'الطلب';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الطلبات';
    }

    public static function form(Schema $schema): Schema
    {
        return WorkoutRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutRequestsTable::configure($table);
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
            'index' => ListWorkoutRequests::route('/'),
            'create' => CreateWorkoutRequest::route('/create'),
            'edit' => EditWorkoutRequest::route('/{record}/edit'),
        ];
    }
}
