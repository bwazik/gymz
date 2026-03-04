<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Enums\UserLevel;
use App\Rules\PhoneNumber;
use Filament\Forms\Components\DatePicker;
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
                FileUpload::make('image_path')
                    ->label(__('الصورة'))
                    ->image()
                    ->directory('users')
                    ->disk('public')
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(3072)
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label(__('الاسم'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('البريد الإلكتروني'))
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(__('رقم الهاتف'))
                    ->rule(new PhoneNumber, fn($component) => $component->getState())
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(__('كلمة المرور'))
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->maxLength(255),
                Select::make('gender')
                    ->label(__('الجنس'))
                    ->options(Gender::class)
                    ->searchable()
                    ->required()
                    ->default(Gender::Male),
                DatePicker::make('dob')
                    ->label(__('تاريخ الميلاد')),
                Select::make('level')
                    ->label(__('المستوى'))
                    ->options(UserLevel::class)
                    ->searchable()
                    ->required()
                    ->default(UserLevel::Mid),
                TextInput::make('glutes_balance')
                    ->label(__('الجلوتس'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reliability_score')
                    ->label(__('نقاط الموثوقية'))
                    ->required()
                    ->numeric()
                    ->default(100)
                    ->columnSpanFull(),
                Toggle::make('is_admin')
                    ->label(__('صلاحية الإدارة'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
