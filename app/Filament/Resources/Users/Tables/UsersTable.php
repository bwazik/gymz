<?php

namespace App\Filament\Resources\Users\Tables;

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
                    ->label(__('النقاط'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reliability_score')
                    ->label(__('الموثوقية'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_admin')
                    ->label(__('مدير'))
                    ->boolean(),
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
                ViewAction::make()->label(false)->tooltip('عرض'),
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
