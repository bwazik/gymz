<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class UserRegistrationsChart extends ChartWidget
{
    protected ?string $heading = 'تسجيلات المستخدمين';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as aggregate')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'مستخدمين جدد',
                    'data' => $data->pluck('aggregate')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#93c5fd',
                ],
            ],
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
