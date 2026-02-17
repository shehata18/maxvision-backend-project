<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactSubmissionStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $total = ContactSubmission::count();
        $newThisWeek = ContactSubmission::new()->where('created_at', '>=', now()->startOfWeek())->count();
        $newLastWeek = ContactSubmission::new()->whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();
        $newThisMonth = ContactSubmission::new()->where('created_at', '>=', now()->startOfMonth())->count();
        $newLastMonth = ContactSubmission::new()->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();
        $converted = ContactSubmission::converted()->count();
        $conversionRate = $total > 0 ? round(($converted / $total) * 100, 1) : 0;

        return [
            Stat::make('Total Submissions', $total)
                ->description('All time')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('primary'),
            Stat::make('New This Week', $newThisWeek)
                ->description($this->getTrendDescription($newThisWeek, $newLastWeek))
                ->descriptionIcon($newThisWeek >= $newLastWeek ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('info'),
            Stat::make('New This Month', $newThisMonth)
                ->description($this->getTrendDescription($newThisMonth, $newLastMonth))
                ->descriptionIcon($newThisMonth >= $newLastMonth ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('warning'),
            Stat::make('Conversion Rate', $conversionRate . '%')
                ->description($converted . ' converted of ' . $total . ' total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }

    private function getTrendDescription(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? '+' . $current . ' new' : 'No change';
        }

        $diff = $current - $previous;
        $prefix = $diff >= 0 ? '+' : '';

        return $prefix . $diff . ' vs previous period';
    }
}
