<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Gender;
use App\Enums\UserLevel;
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
                    ->disk('public')
                    ->directory('users')
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
                    ->tel()
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
                    ->options(Gender::class),
                DatePicker::make('dob')
                    ->label(__('تاريخ الميلاد')),
                Select::make('level')
                    ->label(__('المستوى'))
                    ->options(UserLevel::class)
                    ->required()
                    ->default(UserLevel::BEGINNER),
                TextInput::make('glutes_balance')
                    ->label(__('رصيد النقاط (Glutes)'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reliability_score')
                    ->label(__('نقاط الموثوقية'))
                    ->required()
                    ->numeric()
                    ->default(100),
                Toggle::make('is_admin')
                    ->label(__('صلاحية الإدارة'))
                    ->required(),
            ]);
    }
}
