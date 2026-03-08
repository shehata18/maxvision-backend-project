<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Product::count();
        $active = Product::where('is_active', true)->count();
        $inactive = $total - $active;
        
        // Category distribution
        $outdoor = Product::where('category', 'outdoor')->count();
        $indoor = Product::where('category', 'indoor')->count();
        $transparent = Product::where('category', 'transparent')->count();
        $posters = Product::where('category', 'posters')->count();
        
        // Most popular category
        $categories = [
            'Outdoor' => $outdoor,
            'Indoor' => $indoor,
            'Transparent' => $transparent,
            'Posters' => $posters,
        ];
        arsort($categories);
        $topCategory = array_key_first($categories);
        $topCategoryCount = $categories[$topCategory];
        
        // Recent additions
        $addedThisMonth = Product::where('created_at', '>=', now()->startOfMonth())->count();
        $addedLastMonth = Product::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();
        
        $monthTrend = $addedThisMonth - $addedLastMonth;
        $monthTrendDesc = $addedLastMonth === 0
            ? ($addedThisMonth > 0 ? "+{$addedThisMonth} new" : 'No new products')
            : ($monthTrend >= 0 ? "+{$monthTrend}" : "{$monthTrend}") . ' vs last month';

        return [
            Stat::make('Total Products', $total)
                ->description("{$active} active, {$inactive} inactive")
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 25, $total]),
            
            Stat::make('Active Products', $active)
                ->description(round(($active / max($total, 1)) * 100, 1) . '% of total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Top Category', $topCategory)
                ->description("{$topCategoryCount} products")
                ->descriptionIcon('heroicon-o-tag')
                ->color('info'),
            
            Stat::make('Added This Month', $addedThisMonth)
                ->description($monthTrendDesc)
                ->descriptionIcon($monthTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('warning'),
        ];
    }
}
