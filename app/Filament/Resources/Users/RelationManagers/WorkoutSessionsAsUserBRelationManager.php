<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WorkoutSessionsAsUserBRelationManager extends RelationManager
{
    protected static string $relationship = 'workoutSessionsAsUserB';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('جلسات التمرين (مقدم طلب التمرين)');
    }

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
                TextColumn::make('scanned_at')
                    ->label(__('تاريخ المسح'))
                    ->isoDateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('الحالة'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label(__('تاريخ الحذف'))
                    ->isoDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('تاريخ الإضافة'))
                    ->isoDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('تاريخ التعديل'))
                    ->isoDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
