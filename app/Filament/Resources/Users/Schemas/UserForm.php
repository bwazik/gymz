<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Enums\UserLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('bio')
                    ->maxLength(65535),
                Select::make('gender')
                    ->options(Gender::class),
                Select::make('level')
                    ->options(UserLevel::class)
                    ->required(),
                TextInput::make('glutes_balance')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reliability_score')
                    ->required()
                    ->numeric()
                    ->default(100),
                Toggle::make('is_admin')
                    ->required(),
            ]);
    }
}
