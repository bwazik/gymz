<?php

namespace App\Filament\Resources\WorkoutSessions;

use App\Enums\SessionStatus;
use App\Filament\Resources\WorkoutSessions\Pages\ListWorkoutSessions;
use App\Models\WorkoutSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkoutSessionResource extends Resource
{
    protected static ?string $model = WorkoutSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'جلسة التمرين';
    }

    public static function getPluralModelLabel(): string
    {
        return 'جلسات التمارين المكتملة';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('intent_id')
                    ->relationship('workoutIntent', 'start_time')
                    ->required(),
                Select::make('user_a_id')
                    ->relationship('userA', 'name')
                    ->required(),
                Select::make('user_b_id')
                    ->relationship('userB', 'name')
                    ->required(),
                TextInput::make('qr_token')
                    ->maxLength(255),
                DateTimePicker::make('scanned_at'),
                Select::make('status')
                    ->options(SessionStatus::class)
                    ->required()
                    ->default(SessionStatus::PENDING),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workoutIntent.start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('userA.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userB.name')
                    ->searchable()
                    ->sortable(),
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
            'index' => ListWorkoutSessions::route('/'),
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
