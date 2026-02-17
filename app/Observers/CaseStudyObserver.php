<?php

namespace App\Observers;

use App\Models\CaseStudy;
use App\Services\ImageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CaseStudyObserver
{
    /**
     * Handle the CaseStudy "saved" event (covers both created and updated).
     */
    public function saved(CaseStudy $caseStudy): void
    {
        $this->clearCaseStudyCaches($caseStudy);
    }

    /**
     * Handle the CaseStudy "deleted" event.
     */
    public function deleted(CaseStudy $caseStudy): void
    {
        // Clean up images
        try {
            app(ImageService::class)->delete($caseStudy->image);
        } catch (\Exception $e) {
            Log::warning('Failed to delete case study image', [
                'case_study' => $caseStudy->slug,
                'error' => $e->getMessage(),
            ]);
        }

        $this->clearCaseStudyCaches($caseStudy);
    }

    /**
     * Clear all case-study-related caches.
     */
    private function clearCaseStudyCaches(CaseStudy $caseStudy): void
    {
        Cache::forget("case_study.{$caseStudy->slug}");
        Cache::forget('case_studies.list...');    // no filters
        Cache::forget("case_studies.list.{$caseStudy->industry}.");
        Cache::forget("case_studies.list..1");    // featured
        Cache::forget("case_studies.list.{$caseStudy->industry}.1");

        Log::info('Case study caches cleared', ['case_study' => $caseStudy->slug]);
    }
}
