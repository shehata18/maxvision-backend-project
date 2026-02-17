<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContactSubmissionStatsWidget;
use App\Filament\Widgets\ContactSubmissionsChartWidget;
use App\Filament\Widgets\PopularProductsWidget;
use App\Filament\Widgets\ProductsByCategoryWidget;
use App\Filament\Widgets\RecentContactSubmissionsWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            ContactSubmissionStatsWidget::class,
            PopularProductsWidget::class,
            ProductsByCategoryWidget::class,
            ContactSubmissionsChartWidget::class,
            RecentContactSubmissionsWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 12;
    }
}
