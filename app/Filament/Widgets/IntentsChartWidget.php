<?php

namespace App\Filament\Widgets;

use App\Models\WorkoutIntent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IntentsChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected ?string $heading = 'طلبات التمرين (آخر 7 أيام)';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels[] = $date;
            $data[] = WorkoutIntent::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'طلبات التمرين',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
