<?php

namespace App\Filament\Widgets;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Completed Sessions', WorkoutSession::where('status', SessionStatus::Completed)->count()),
            Stat::make('Total Glutes in Economy', User::sum('glutes_balance')),
        ];
    }
}
