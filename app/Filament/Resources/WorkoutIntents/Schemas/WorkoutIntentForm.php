<?php

namespace App\Filament\Resources\WorkoutIntents\Schemas;

use App\Enums\IntentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkoutIntentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('المستخدم'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('gym_id')
                    ->label(__('الجيم'))
                    ->relationship('gym', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('workout_category_id')
                    ->label(__('فئة التمرين'))
                    ->relationship('workoutCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('workout_target_id')
                    ->label(__('الهدف العضلي'))
                    ->relationship('workoutTarget', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label(__('وقت البدء'))
                    ->required()
                    ->default(now())
                    ->columnSpanFull(),
                Toggle::make('has_invitation')
                    ->label(__('دعوة ضيف'))
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('note')
                    ->label(__('ملاحظة'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('الحالة'))
                    ->options(IntentStatus::class)
                    ->required()
                    ->searchable()
                    ->default(0)
                    ->columnSpanFull(),
            ]);
    }
}
