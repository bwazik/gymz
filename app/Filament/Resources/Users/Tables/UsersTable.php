<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label(__('الصورة'))
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('images/default.jpg')),
                TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('البريد الإلكتروني'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label(__('رقم الهاتف'))
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label(__('الجنس'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('level')
                    ->label(__('المستوى'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('glutes_balance')
                    ->label(__('الجلوتس'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reliability_score')
                    ->label(__('الموثوقية'))
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_admin')
                    ->label(__('مدير'))
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
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
                SelectFilter::make('level')
                    ->label(__('المستوى'))
                    ->options(collect(UserLevel::cases())->mapWithKeys(fn(UserLevel $case) => [$case->value => $case->getLabel()])),
                TernaryFilter::make('is_admin')
                    ->label(__('مدير'))
                    ->placeholder(__('الكل'))
                    ->trueLabel('نعم')
                    ->falseLabel('لا'),
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
