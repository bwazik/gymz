<?php

namespace App\Filament\Resources\GlutesTransactions\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GlutesTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('المستخدم'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('النوع'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('المبلغ'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('الوصف'))
                    ->searchable(),
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
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
