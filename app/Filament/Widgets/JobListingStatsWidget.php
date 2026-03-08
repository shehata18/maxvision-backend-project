<?php

namespace App\Filament\Widgets;

use App\Models\JobListing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JobListingStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = JobListing::count();
        $active = JobListing::where('is_active', true)->count();
        $featured = JobListing::where('is_featured', true)->count();
        $inactive = $total - $active;
        
        // Category distribution
        $engineering = JobListing::where('category', 'engineering')->count();
        $sales = JobListing::where('category', 'sales')->count();
        $marketing = JobListing::where('category', 'marketing')->count();
        $operations = JobListing::where('category', 'operations')->count();
        $support = JobListing::where('category', 'customer_support')->count();
        
        // Most popular category
        $categories = [
            'Engineering' => $engineering,
            'Sales' => $sales,
            'Marketing' => $marketing,
            'Operations' => $operations,
            'Support' => $support,
        ];
        arsort($categories);
        $topCategory = array_key_first($categories);
        $topCategoryCount = $categories[$topCategory];
        
        // Job type distribution
        $fullTime = JobListing::where('job_type', 'full_time')->count();
        $partTime = JobListing::where('job_type', 'part_time')->count();
        $contract = JobListing::where('job_type', 'contract')->count();
        
        // Recent postings
        $postedThisMonth = JobListing::where('posted_at', '>=', now()->startOfMonth())->count();
        $postedLastMonth = JobListing::whereBetween('posted_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();
        
        $monthTrend = $postedThisMonth - $postedLastMonth;
        $monthTrendDesc = $postedLastMonth === 0
            ? ($postedThisMonth > 0 ? "+{$postedThisMonth} new" : 'No new postings')
            : ($monthTrend >= 0 ? "+{$monthTrend}" : "{$monthTrend}") . ' vs last month';

        return [
            Stat::make('Total Job Listings', $total)
                ->description("{$active} active, {$inactive} inactive")
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('primary')
                ->chart([5, 8, 10, 12, 15, 18, $total]),
            
            Stat::make('Active Positions', $active)
                ->description("{$featured} featured positions")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Top Category', $topCategory)
                ->description("{$topCategoryCount} positions")
                ->descriptionIcon('heroicon-o-tag')
                ->color('info'),
            
            Stat::make('Posted This Month', $postedThisMonth)
                ->description($monthTrendDesc)
                ->descriptionIcon($monthTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('warning'),
        ];
    }
}
