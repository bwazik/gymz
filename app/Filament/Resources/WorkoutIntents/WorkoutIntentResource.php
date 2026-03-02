<?php

namespace App\Filament\Resources\WorkoutIntents;

use App\Enums\IntentStatus;
use App\Filament\Resources\WorkoutIntents\Pages\ListWorkoutIntents;
use App\Models\WorkoutIntent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkoutIntentResource extends Resource
{
    protected static ?string $model = WorkoutIntent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'نية التمرين';
    }

    public static function getPluralModelLabel(): string
    {
        return 'نوايا التمارين (Intents)';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('gym_id')
                    ->relationship('gym', 'name')
                    ->required(),
                Select::make('workout_target_id')
                    ->relationship('workoutTarget', 'name')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                Toggle::make('has_invitation')
                    ->default(false),
                Select::make('status')
                    ->options(IntentStatus::class)
                    ->required()
                    ->default(IntentStatus::ACTIVE),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gym.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('workoutTarget.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('has_invitation')
                    ->boolean(),
                TextColumn::make('status')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListWorkoutIntents::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
