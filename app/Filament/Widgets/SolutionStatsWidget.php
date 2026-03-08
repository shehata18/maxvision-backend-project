<?php

namespace App\Filament\Widgets;

use App\Models\Solution;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SolutionStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Solution::count();
        $active = Solution::where('is_active', true)->count();
        $inactive = $total - $active;
        
        // Category distribution
        $retail = Solution::where('category', 'retail')->count();
        $outdoor = Solution::where('category', 'outdoor')->count();
        $corporate = Solution::where('category', 'corporate')->count();
        $events = Solution::where('category', 'events')->count();
        $architecture = Solution::where('category', 'architecture')->count();
        
        // Most popular category
        $categories = [
            'Retail' => $retail,
            'Outdoor' => $outdoor,
            'Corporate' => $corporate,
            'Events' => $events,
            'Architecture' => $architecture,
        ];
        arsort($categories);
        $topCategory = array_key_first($categories);
        $topCategoryCount = $categories[$topCategory];
        
        // Solutions with products
        $withProducts = Solution::has('recommendedProducts')->count();
        $withoutProducts = $total - $withProducts;
        
        // Recent additions
        $addedThisMonth = Solution::where('created_at', '>=', now()->startOfMonth())->count();

        return [
            Stat::make('Total Solutions', $total)
                ->description("{$active} active, {$inactive} inactive")
                ->descriptionIcon('heroicon-o-light-bulb')
                ->color('primary')
                ->chart([3, 5, 7, 9, 11, 13, $total]),
            
            Stat::make('Active Solutions', $active)
                ->description(round(($active / max($total, 1)) * 100, 1) . '% of total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Top Category', $topCategory)
                ->description("{$topCategoryCount} solutions")
                ->descriptionIcon('heroicon-o-tag')
                ->color('info'),
            
            Stat::make('With Products', $withProducts)
                ->description("{$withoutProducts} without products")
                ->descriptionIcon('heroicon-o-cube')
                ->color('warning'),
        ];
    }
}
