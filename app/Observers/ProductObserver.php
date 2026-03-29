<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ImageService;
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
        // Clean up images
        try {
            $imageService = app(ImageService::class);
            $imageService->delete($product->image);
            if (!empty($product->gallery)) {
                $imageService->deleteMultiple($product->gallery);
            }
        }
        catch (\Exception $e) {
            Log::warning('Failed to delete product images', [
                'product' => $product->slug,
                'error' => $e->getMessage(),
            ]);
        }

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

        // Clear all product list caches by flushing the entire cache
        // This is aggressive but ensures consistency
        // For production with Redis, consider using Cache::tags(['products'])->flush()
        try {
            // Get all cache keys and clear product-related ones
            $cacheDriver = config('cache.default');
            
            if ($cacheDriver === 'file') {
                // For file cache, we need to clear all product list caches manually
                // Clear common cache key patterns
                $categories = [null, 'outdoor', 'indoor', 'transparent', 'posters', 'rental', 'controllers'];
                $perPageOptions = [12, 24, 48];
                $pages = range(1, 10); // Clear first 10 pages
                
                foreach ($categories as $category) {
                    foreach ($perPageOptions as $perPage) {
                        foreach ($pages as $page) {
                            // Build cache key matching the controller format
                            $key = "products.list.{$category}.....{$perPage}.page.{$page}";
                            Cache::forget($key);
                        }
                    }
                }
                
                // Also clear keys without filters
                foreach ($perPageOptions as $perPage) {
                    foreach ($pages as $page) {
                        Cache::forget("products.list.....{$perPage}.page.{$page}");
                    }
                }
            } else {
                // For Redis/Memcached, you could use tags or patterns
                // Cache::tags(['products'])->flush();
                
                // Fallback: clear common patterns
                $categories = [null, 'outdoor', 'indoor', 'transparent', 'posters', 'rental', 'controllers'];
                foreach ($categories as $category) {
                    for ($page = 1; $page <= 10; $page++) {
                        Cache::forget("products.list.{$category}.....12.page.{$page}");
                        Cache::forget("products.list.{$category}.....24.page.{$page}");
                    }
                }
            }
        }
        catch (\Exception $e) {
            Log::warning('Failed to clear product cache', [
                'error' => $e->getMessage(),
            ]);
        }

        Log::info('Product caches cleared', ['product' => $product->slug]);
    }
}
