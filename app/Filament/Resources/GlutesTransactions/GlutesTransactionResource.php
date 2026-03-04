<?php

namespace App\Filament\Resources\GlutesTransactions;

use App\Enums\TransactionType;
use App\Filament\Resources\GlutesTransactions\Pages\ListGlutesTransactions;
use App\Filament\Resources\GlutesTransactions\Schemas\GlutesTransactionForm;
use App\Filament\Resources\GlutesTransactions\Tables\GlutesTransactionsTable;
use App\Models\GlutesTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class GlutesTransactionResource extends Resource
{
    protected static ?string $model = GlutesTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return 'جلوتس';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الجلوتس';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return GlutesTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GlutesTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGlutesTransactions::route('/'),
        ];
    }
}
