<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $analytics = app(AnalyticsService::class);

        $totalProducts = $analytics->getTotalProducts();
        $activeSolutions = $analytics->getActiveSolutions();
        $activeCaseStudies = $analytics->getActiveCaseStudies();

        $thisWeek = $analytics->getContactSubmissionsThisWeek();
        $lastWeek = $analytics->getContactSubmissionsLastWeek();
        $diff = $thisWeek - $lastWeek;

        $trendDesc = $lastWeek === 0
            ? ($thisWeek > 0 ? "+{$thisWeek} new" : 'No submissions')
            : ($diff >= 0 ? "+{$diff}" : "{$diff}") . ' vs last week';

        $trendIcon = $thisWeek >= $lastWeek
            ? 'heroicon-o-arrow-trending-up'
            : 'heroicon-o-arrow-trending-down';

        return [
            Stat::make('Total Products', $totalProducts)
                ->description('All products')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Active Solutions', $activeSolutions)
                ->description('All active')
                ->descriptionIcon('heroicon-o-light-bulb')
                ->color('info'),

            Stat::make('Case Studies', $activeCaseStudies)
                ->description('All active')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make('Contact Inquiries', $thisWeek)
                ->description($trendDesc)
                ->descriptionIcon($trendIcon)
                ->color('success'),
        ];
    }
}
