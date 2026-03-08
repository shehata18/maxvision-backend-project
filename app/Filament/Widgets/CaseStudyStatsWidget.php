<?php

namespace App\Filament\Widgets;

use App\Models\CaseStudy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CaseStudyStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = CaseStudy::count();
        $active = CaseStudy::where('is_active', true)->count();
        $featured = CaseStudy::where('is_featured', true)->count();
        
        // Industry distribution
        $retail = CaseStudy::where('industry', 'retail')->count();
        $outdoor = CaseStudy::where('industry', 'outdoor')->count();
        $corporate = CaseStudy::where('industry', 'corporate')->count();
        $events = CaseStudy::where('industry', 'events')->count();
        $architecture = CaseStudy::where('industry', 'architecture')->count();
        
        // Most popular industry
        $industries = [
            'Retail' => $retail,
            'Outdoor' => $outdoor,
            'Corporate' => $corporate,
            'Events' => $events,
            'Architecture' => $architecture,
        ];
        arsort($industries);
        $topIndustry = array_key_first($industries);
        $topIndustryCount = $industries[$topIndustry];
        
        // Case studies with products
        $withProducts = CaseStudy::has('products')->count();
        
        // Recent additions
        $addedThisYear = CaseStudy::where('created_at', '>=', now()->startOfYear())->count();
        $addedLastYear = CaseStudy::whereBetween('created_at', [
            now()->subYear()->startOfYear(),
            now()->subYear()->endOfYear(),
        ])->count();
        
        $yearTrend = $addedThisYear - $addedLastYear;
        $yearTrendDesc = $addedLastYear === 0
            ? ($addedThisYear > 0 ? "+{$addedThisYear} this year" : 'No new studies')
            : ($yearTrend >= 0 ? "+{$yearTrend}" : "{$yearTrend}") . ' vs last year';

        return [
            Stat::make('Total Case Studies', $total)
                ->description("{$active} active, {$featured} featured")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([2, 4, 6, 8, 10, 12, $total]),
            
            Stat::make('Active Studies', $active)
                ->description(round(($active / max($total, 1)) * 100, 1) . '% of total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Top Industry', $topIndustry)
                ->description("{$topIndustryCount} case studies")
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),
            
            Stat::make('Added This Year', $addedThisYear)
                ->description($yearTrendDesc)
                ->descriptionIcon($yearTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('warning'),
        ];
    }
}
