<?php

namespace App\Filament\Resources\Gyms\RelationManagers;

use App\Filament\Resources\WorkoutIntents\WorkoutIntentResource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WorkoutIntentsRelationManager extends RelationManager
{
    protected static string $relationship = 'workoutIntents';

    protected static ?string $relatedResource = WorkoutIntentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('المستخدم'))
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
                    ->searchable(),
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
                TrashedFilter::make()
            ])
            ->recordActions([
                EditAction::make()->label(false)->tooltip('تعديل'),
                DeleteAction::make()->label(false)->tooltip('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
