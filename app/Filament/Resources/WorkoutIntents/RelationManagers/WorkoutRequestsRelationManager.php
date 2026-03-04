<?php

namespace App\Filament\Resources\WorkoutIntents\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\Enums\RequestStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkoutRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'workoutRequests';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sender_id')
                    ->label(__('المرسل'))
                    ->relationship('sender', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label(__('الحالة'))
                    ->options(RequestStatus::class)
                    ->required()
                    ->default(RequestStatus::PENDING),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('sender.name')
                    ->label(__('المرسل'))
                    ->searchable()
                    ->sortable(),
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label(false)->tooltip('تعديل'),
                DeleteAction::make()->label(false)->tooltip('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
