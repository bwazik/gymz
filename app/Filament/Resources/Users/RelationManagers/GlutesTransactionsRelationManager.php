<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GlutesTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'glutesTransactions';

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
            ->recordTitleAttribute('created_at')
            ->columns([
                TextColumn::make('type')
                    ->label(__('النوع'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('القيمة'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('الوصف'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('تاريخ الإضافة'))
                    ->dateTime()
                    ->sortable(),
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
