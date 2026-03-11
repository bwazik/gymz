<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class TopReferrersWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'أفضل المُحيلين';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => 
                User::query()
                    ->withCount('referrals')
                    ->having('referrals_count', '>', 0)
                    ->orderByDesc('referrals_count')
                    ->limit(10)
            )
            ->columns([
                ImageColumn::make('image_path')
                    ->label(__('الصورة'))
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('images/default.jpg')),
                TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->searchable(),
                TextColumn::make('level')
                    ->label(__('المستوى'))
                    ->badge(),
                TextColumn::make('referrals_count')
                    ->label(__('عدد الدعوات'))
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
