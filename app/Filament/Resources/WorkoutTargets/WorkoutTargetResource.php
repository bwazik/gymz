<?php

namespace App\Filament\Resources\WorkoutTargets;

use App\Filament\Resources\WorkoutTargets\Pages\CreateWorkoutTarget;
use App\Filament\Resources\WorkoutTargets\Pages\EditWorkoutTarget;
use App\Filament\Resources\WorkoutTargets\Pages\ListWorkoutTargets;
use App\Filament\Resources\WorkoutTargets\Schemas\WorkoutTargetForm;
use App\Filament\Resources\WorkoutTargets\Tables\WorkoutTargetsTable;
use App\Filament\Resources\WorkoutTargets\RelationManagers\WorkoutIntentsRelationManager;
use App\Models\WorkoutTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkoutTargetResource extends Resource
{
    protected static ?string $model = WorkoutTarget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewfinderCircle;

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'الهدف العضلي';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الأهداف العضلية';
    }

    public static function form(Schema $schema): Schema
    {
        return WorkoutTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutTargetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            WorkoutIntentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkoutTargets::route('/'),
            'create' => CreateWorkoutTarget::route('/create'),
            'edit' => EditWorkoutTarget::route('/{record}/edit'),
        ];
    }
}
