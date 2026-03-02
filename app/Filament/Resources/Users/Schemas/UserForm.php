<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Enums\UserLevel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Toggle::make('is_admin')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('gender')
                    ->options(Gender::class),
                DatePicker::make('dob'),
                Select::make('level')
                    ->options(UserLevel::class)
                    ->required()
                    ->default(0),
                TextInput::make('glutes_balance')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reliability_score')
                    ->required()
                    ->numeric()
                    ->default(100),
                FileUpload::make('image_path')
                    ->image(),
            ]);
    }
}
