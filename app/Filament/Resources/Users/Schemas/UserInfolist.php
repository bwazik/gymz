<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                IconEntry::make('is_admin')
                    ->boolean(),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->badge()
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('dob')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('level')
                    ->badge()
                    ->numeric(),
                TextEntry::make('glutes_balance')
                    ->numeric(),
                TextEntry::make('reliability_score')
                    ->numeric(),
                ImageEntry::make('image_path')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (User $record): bool => $record->trashed()),
            ]);
    }
}
