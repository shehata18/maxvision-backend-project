<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "saved" event (covers both created and updated).
     */
    public function saved(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Clear all product-related caches when a product changes.
     */
    private function clearProductCaches(Product $product): void
    {
        // Clear the specific product detail cache
        Cache::forget("product.{$product->slug}");

        // Clear the categories cache (counts may have changed)
        Cache::forget('products.categories');

        // Clear all list caches by pattern
        // Since file cache doesn't support tags/patterns, we clear common combinations
        // For a production app with Redis, use Cache::tags(['products'])->flush()
        try {
            $cacheStore = Cache::getStore();

            // If using a store that supports flush (like array/redis), clear product keys
            // For file-based cache, we invalidate by clearing known keys
            $categories = [null, 'outdoor', 'indoor', 'transparent', 'posters'];
            foreach ($categories as $category) {
                // Clear first page of each category (most common cache)
                $key = "products.list.{$category}......12.page.1";
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to clear product cache', [
                'error' => $e->getMessage(),
            ]);
        }

        Log::info('Product caches cleared', ['product' => $product->slug]);
    }
}
