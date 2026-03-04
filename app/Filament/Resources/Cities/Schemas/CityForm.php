<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('الاسم'))
                    ->required()
                    ->maxLength(255)
                    ->minLength(2),
                Toggle::make('is_active')
                    ->label(__('الحالة'))
                    ->required()
                    ->default(true)
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->visibleOn('edit')
                    ->hiddenOn('create')
                    ->columnSpanFull(),
            ]);
    }
}
