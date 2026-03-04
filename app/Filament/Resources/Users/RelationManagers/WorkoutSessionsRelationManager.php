<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WorkoutSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'workoutSessionsAsUserA';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('userA.name')
                    ->label(__('المستخدم الأول'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userB.name')
                    ->label(__('المستخدم الثاني'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('الحالة'))
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
