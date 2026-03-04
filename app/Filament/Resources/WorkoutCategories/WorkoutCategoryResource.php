<?php

namespace App\Filament\Resources\WorkoutCategories;

use App\Filament\Resources\WorkoutCategories\Pages\CreateWorkoutCategory;
use App\Filament\Resources\WorkoutCategories\Pages\EditWorkoutCategory;
use App\Filament\Resources\WorkoutCategories\Pages\ListWorkoutCategories;
use App\Filament\Resources\WorkoutCategories\Schemas\WorkoutCategoryForm;
use App\Filament\Resources\WorkoutCategories\Tables\WorkoutCategoriesTable;
use App\Filament\Resources\WorkoutCategories\RelationManagers\WorkoutTargetsRelationManager;
use App\Models\WorkoutCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkoutCategoryResource extends Resource
{
    protected static ?string $model = WorkoutCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'نوع التمرين';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أنواع التمارين';
    }

    public static function form(Schema $schema): Schema
    {
        return WorkoutCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            WorkoutTargetsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkoutCategories::route('/'),
            'create' => CreateWorkoutCategory::route('/create'),
            'edit' => EditWorkoutCategory::route('/{record}/edit'),
        ];
    }
}
