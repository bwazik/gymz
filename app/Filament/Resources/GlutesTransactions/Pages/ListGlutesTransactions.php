<?php

namespace App\Filament\Resources\GlutesTransactions\Pages;

use App\Filament\Resources\GlutesTransactions\GlutesTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGlutesTransactions extends ListRecords
{
    protected static string $resource = GlutesTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
