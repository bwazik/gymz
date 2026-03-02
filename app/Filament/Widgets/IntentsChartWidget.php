<?php

namespace App\Filament\Widgets;

use App\Models\WorkoutIntent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IntentsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Workout Intents (Last 7 Days)';

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
                    'label' => 'Workout Intents',
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
