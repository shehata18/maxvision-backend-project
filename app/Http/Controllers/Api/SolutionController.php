<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionIndexRequest;
use App\Http\Resources\SolutionDetailResource;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SolutionController extends Controller
{
    /**
     * Get list of active solutions with optional category filtering.
     *
     * @param SolutionIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
     *
     * @queryParam category string Filter by category (retail, outdoor, corporate, events, architecture)
     */
    public function index(SolutionIndexRequest $request)
    {
        $validated = $request->validated();
        $category = $validated['category'] ?? null;

        $cacheKey = "solutions.list.{$category}";

        try {
            $solutions = Cache::remember($cacheKey, 3600, function () use ($category) {
                $query = Solution::active()
                    ->with(['benefits', 'specs', 'recommendedProducts'])
                    ->select(['id', 'slug', 'title', 'tagline', 'description', 'category', 'image', 'is_active']);

                if ($category) {
                    $query->byCategory($category);
                }

                return $query->orderBy('title')->get();
            });

            return SolutionResource::collection($solutions);
        } catch (\Exception $e) {
            Log::error('Failed to fetch solutions', [
                'error' => $e->getMessage(),
                'category' => $category,
            ]);

            return response()->json([
                'message' => 'Failed to fetch solutions.',
            ], 500);
        }
    }

    /**
     * Get a single solution by slug with full details.
     *
     * @param string $slug
     * @return SolutionDetailResource|JsonResponse
     *
     * @urlParam slug string required The solution slug. Example: retail-digital-signage
     */
    public function show(string $slug)
    {
        try {
            $solution = Cache::remember("solution.{$slug}", 7200, function () use ($slug) {
                return Solution::active()
                    ->with(['benefits', 'specs', 'recommendedProducts'])
                    ->where('slug', $slug)
                    ->first();
            });

            if (!$solution) {
                return response()->json([
                    'message' => 'Solution not found.',
                ], 404);
            }

            return new SolutionDetailResource($solution);
        } catch (\Exception $e) {
            Log::error('Failed to fetch solution detail', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch solution details.',
            ], 500);
        }
    }
}
