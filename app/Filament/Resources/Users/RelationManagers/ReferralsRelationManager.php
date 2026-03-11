<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrals';

    protected static ?string $title = 'المدعوين';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
                    ->sortable(),
                IconColumn::make('is_onboarded')
                    ->label(__('مسجل حساب'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('تاريخ التسجيل'))
                    ->isoDateTime()
                    ->sortable(),
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
