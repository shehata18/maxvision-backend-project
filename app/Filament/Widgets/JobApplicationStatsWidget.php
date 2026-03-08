<?php

namespace App\Filament\Widgets;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JobApplicationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = JobApplication::count();
        
        // Status distribution
        $pending = JobApplication::where('status', JobApplicationStatus::Pending->value)->count();
        $reviewing = JobApplication::where('status', JobApplicationStatus::Reviewing->value)->count();
        $shortlisted = JobApplication::where('status', JobApplicationStatus::Shortlisted->value)->count();
        $interviewed = JobApplication::where('status', JobApplicationStatus::Interviewed->value)->count();
        $offered = JobApplication::where('status', JobApplicationStatus::Offered->value)->count();
        $hired = JobApplication::where('status', JobApplicationStatus::Hired->value)->count();
        $rejected = JobApplication::where('status', JobApplicationStatus::Rejected->value)->count();
        
        // Active applications (not rejected or hired)
        $active = $pending + $reviewing + $shortlisted + $interviewed + $offered;
        
        // Success rate
        $successRate = $total > 0 ? round((($hired + $offered) / $total) * 100, 1) : 0;
        
        // Recent applications
        $thisWeek = JobApplication::where('created_at', '>=', now()->startOfWeek())->count();
        $lastWeek = JobApplication::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();
        
        $weekTrend = $thisWeek - $lastWeek;
        $weekTrendDesc = $lastWeek === 0
            ? ($thisWeek > 0 ? "+{$thisWeek} new" : 'No new applications')
            : ($weekTrend >= 0 ? "+{$weekTrend}" : "{$weekTrend}") . ' vs last week';
        
        // General applications
        $generalApps = JobApplication::where('is_general_application', true)->count();
        $specificApps = $total - $generalApps;

        return [
            Stat::make('Total Applications', $total)
                ->description("{$active} active, {$hired} hired")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([10, 15, 20, 25, 30, 35, $total]),
            
            Stat::make('Pending Review', $pending)
                ->description("{$reviewing} under review")
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Success Rate', $successRate . '%')
                ->description("{$hired} hired, {$offered} offered")
                ->descriptionIcon('heroicon-o-trophy')
                ->color('success'),
            
            Stat::make('This Week', $thisWeek)
                ->description($weekTrendDesc)
                ->descriptionIcon($weekTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('info'),
        ];
    }
}
