<?php

namespace App\Filament\Resources\WorkoutIntents\Tables;

use App\Enums\IntentStatus;
use App\Models\Gym;
use App\Models\User;
use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                    ->isoDateTime()
                    ->sortable(),
                ToggleColumn::make('has_invitation')
                    ->label(__('دعوة ضيف'))
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('الحالة'))
                    ->badge()
                    ->sortable(),
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
                TextColumn::make('deleted_at')
                    ->label(__('تاريخ الحذف'))
                    ->isoDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label(__('الحالة'))
                    ->options(collect(IntentStatus::cases())->mapWithKeys(fn(IntentStatus $case) => [$case->value => $case->getLabel()])),
                TernaryFilter::make('has_invitation')
                    ->label(__('دعوة ضيف'))
                    ->placeholder(__('الكل'))
                    ->trueLabel('نعم')
                    ->falseLabel('لا'),
                SelectFilter::make('user_id')
                    ->label(__('المستخدم'))
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('gym_id')
                    ->label(__('الجيم'))
                    ->options(Gym::query()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('workout_category_id')
                    ->label(__('نوع التمرين'))
                    ->options(WorkoutCategory::query()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('workout_target_id')
                    ->label(__('الهدف العضلي'))
                    ->options(WorkoutTarget::query()->pluck('name', 'id'))
                    ->searchable(),
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
