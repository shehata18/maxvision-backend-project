<?php

namespace App\Services;

use App\Models\CaseStudy;
use App\Models\ContactSubmission;
use App\Models\Product;
use App\Models\Solution;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    /**
     * Get the total count of all products.
     */
    public function getTotalProducts(): int
    {
        return Cache::remember('analytics.total_products', 900, function () {
            return Product::count();
        });
    }

    /**
     * Get the count of active products.
     */
    public function getActiveProducts(): int
    {
        return Cache::remember('analytics.active_products', 900, function () {
            return Product::active()->count();
        });
    }

    /**
     * Get the count of active solutions.
     */
    public function getActiveSolutions(): int
    {
        return Cache::remember('analytics.active_solutions', 900, function () {
            return Solution::where('is_active', true)->count();
        });
    }

    /**
     * Get the count of active case studies.
     */
    public function getActiveCaseStudies(): int
    {
        return Cache::remember('analytics.active_case_studies', 900, function () {
            return CaseStudy::where('is_active', true)->count();
        });
    }

    /**
     * Get the count of contact submissions received this week.
     */
    public function getContactSubmissionsThisWeek(): int
    {
        return ContactSubmission::where('created_at', '>=', now()->startOfWeek())->count();
    }

    /**
     * Get the count of contact submissions received this month.
     */
    public function getContactSubmissionsThisMonth(): int
    {
        return ContactSubmission::where('created_at', '>=', now()->startOfMonth())->count();
    }

    /**
     * Get the count of contact submissions received last week.
     */
    public function getContactSubmissionsLastWeek(): int
    {
        return ContactSubmission::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();
    }

    /**
     * Get the count of contact submissions received last month.
     */
    public function getContactSubmissionsLastMonth(): int
    {
        return ContactSubmission::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();
    }

    /**
     * Get the most viewed products, ordered by view count.
     *
     * @param  int  $limit
     * @return Collection<int, Product>
     */
    public function getMostViewedProducts(int $limit = 10): Collection
    {
        return Cache::remember("analytics.most_viewed_products.{$limit}", 900, function () use ($limit) {
            return Product::active()
                ->with(['features', 'applications'])
                ->mostViewed($limit)
                ->get();
        });
    }

    /**
     * Get the product count distribution by category.
     *
     * @return \Illuminate\Support\Collection<string, int>
     */
    public function getProductsByCategory(): \Illuminate\Support\Collection
    {
        return Cache::remember('analytics.products_by_category', 900, function () {
            return Product::active()
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category');
        });
    }

    /**
     * Calculate the conversion rate of contact submissions.
     *
     * @return float  Percentage (0-100)
     */
    public function getConversionRate(): float
    {
        $total = ContactSubmission::count();
        if ($total === 0) {
            return 0.0;
        }

        $converted = ContactSubmission::converted()->count();

        return round(($converted / $total) * 100, 1);
    }
}
