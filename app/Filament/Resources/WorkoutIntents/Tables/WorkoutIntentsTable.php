<?php

namespace App\Filament\Resources\WorkoutIntents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WorkoutIntentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('المستخدم'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gym.name')
                    ->label(__('الجيم'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('workoutCategory.name')
                    ->label(__('فئة التمرين'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('workoutTarget.name')
                    ->label(__('الهدف العضلي'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label(__('وقت البدء'))
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('has_invitation')
                    ->label(__('دعوة ضيف'))
                    ->boolean(),
                TextColumn::make('status')
                    ->label(__('الحالة'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('تاريخ الإضافة'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('تاريخ التعديل'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('تاريخ الحذف'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->label(false)->tooltip('تعديل'),
                DeleteAction::make()->label(false)->tooltip('حذف'),
                ForceDeleteAction::make()->label(false)->tooltip('حذف نهائي'),
                RestoreAction::make()->label(false)->tooltip('استعادة'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
