<?php

namespace App\Observers;

use App\Models\Solution;
use App\Services\ImageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SolutionObserver
{
    /**
     * Handle the Solution "saved" event (covers both created and updated).
     */
    public function saved(Solution $solution): void
    {
        $this->clearSolutionCaches($solution);
    }

    /**
     * Handle the Solution "deleted" event.
     */
    public function deleted(Solution $solution): void
    {
        // Clean up images
        try {
            app(ImageService::class)->delete($solution->image);
        } catch (\Exception $e) {
            Log::warning('Failed to delete solution image', [
                'solution' => $solution->slug,
                'error' => $e->getMessage(),
            ]);
        }

        $this->clearSolutionCaches($solution);
    }

    /**
     * Clear all solution-related caches.
     */
    private function clearSolutionCaches(Solution $solution): void
    {
        Cache::forget("solution.{$solution->slug}");
        Cache::forget('solutions.list.');    // no category filter
        Cache::forget("solutions.list.{$solution->category}");

        Log::info('Solution caches cleared', ['solution' => $solution->slug]);
    }
}
