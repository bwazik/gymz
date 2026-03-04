<?php

namespace App\Filament\Widgets;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('جميع المستخدمين', User::count())
                ->description('عدد المستخدمين المسجلين')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('الجلسات المكتملة', WorkoutSession::where('status', SessionStatus::Completed)->count())
                ->description('عدد الجلسات الناجحة')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('مجموع الجلوتس', User::sum('glutes_balance'))
                ->description('إجمالي الرصيد')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('danger'),
        ];
    }
}
