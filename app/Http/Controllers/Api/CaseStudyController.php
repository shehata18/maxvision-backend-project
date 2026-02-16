<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseStudyIndexRequest;
use App\Http\Resources\CaseStudyDetailResource;
use App\Http\Resources\CaseStudyResource;
use App\Models\CaseStudy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CaseStudyController extends Controller
{
    /**
     * Get list of active case studies with optional filtering.
     *
     * @param CaseStudyIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
     *
     * @queryParam industry string Filter by industry (retail, outdoor, corporate, events, architecture)
     * @queryParam featured boolean Filter to only featured case studies
     */
    public function index(CaseStudyIndexRequest $request)
    {
        $validated = $request->validated();
        $industry = $validated['industry'] ?? null;
        $featured = $validated['featured'] ?? null;

        $cacheKey = "case_studies.list.{$industry}.{$featured}";

        try {
            $caseStudies = Cache::remember($cacheKey, 3600, function () use ($industry, $featured) {
                $query = CaseStudy::active()
                    ->with(['metrics', 'specs', 'products']);

                if ($industry) {
                    $query->byIndustry($industry);
                }

                if ($featured !== null) {
                    $query->featured();
                }

                return $query
                    ->orderByDesc('is_featured')
                    ->orderByDesc('date')
                    ->get();
            });

            return CaseStudyResource::collection($caseStudies);
        } catch (\Exception $e) {
            Log::error('Failed to fetch case studies', [
                'error' => $e->getMessage(),
                'industry' => $industry,
                'featured' => $featured,
            ]);

            return response()->json([
                'message' => 'Failed to fetch case studies.',
            ], 500);
        }
    }

    /**
     * Get a single case study by slug with full details.
     *
     * @param string $slug
     * @return CaseStudyDetailResource|JsonResponse
     *
     * @urlParam slug string required The case study slug. Example: times-square-billboard
     */
    public function show(string $slug)
    {
        try {
            $caseStudy = Cache::remember("case_study.{$slug}", 7200, function () use ($slug) {
                return CaseStudy::active()
                    ->with(['metrics', 'specs', 'products'])
                    ->where('slug', $slug)
                    ->first();
            });

            if (!$caseStudy) {
                return response()->json([
                    'message' => 'Case study not found.',
                ], 404);
            }

            return new CaseStudyDetailResource($caseStudy);
        } catch (\Exception $e) {
            Log::error('Failed to fetch case study detail', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch case study details.',
            ], 500);
        }
    }
}
