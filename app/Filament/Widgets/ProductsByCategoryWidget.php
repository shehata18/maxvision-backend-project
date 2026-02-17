<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class ProductsByCategoryWidget extends ChartWidget
{
    protected static ?string $heading = 'Products by Category';

    protected static ?int $sort = 5;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $analytics = app(AnalyticsService::class);
        $distribution = $analytics->getProductsByCategory();

        $categoryLabels = [
            'outdoor' => 'Outdoor',
            'indoor' => 'Indoor',
            'transparent' => 'Transparent',
            'posters' => 'Posters',
        ];

        $labels = [];
        $values = [];

        foreach ($categoryLabels as $key => $label) {
            $labels[] = $label;
            $values[] = $distribution[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => $values,
                    'backgroundColor' => [
                        '#10b981', // green - outdoor
                        '#3b82f6', // blue  - indoor
                        '#f59e0b', // orange - transparent
                        '#ef4444', // red   - posters
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => true,
        ];
    }
}
