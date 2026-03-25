<?php

namespace App\Filament\Resources\Gyms\Schemas;

use App\Models\City;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GymForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_id')
                    ->label(__('المدينة'))
                    ->options(City::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->label(__('الاسم'))
                    ->required()
                    ->maxLength(255)
                    ->minLength(2),
                Select::make('workoutCategories')
                    ->relationship('workoutCategories', 'name')
                    ->multiple()
                    ->preload()
                    ->label(__('الرياضات المدعومة'))
                    ->placeholder(__('اختر الرياضات...'))
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->label(__('خط العرض'))
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90)
                    ->step(0.00000001),
                TextInput::make('longitude')
                    ->label(__('خط الطول'))
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180)
                    ->step(0.00000001),
                FileUpload::make('logo_path')
                    ->label(__('اللوجو'))
                    ->image()
                    ->directory('gym-logos')
                    ->disk('public')
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(3072)
                    ->columnSpanFull(),
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
