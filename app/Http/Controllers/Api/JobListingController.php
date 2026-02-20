<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobListingIndexRequest;
use App\Http\Resources\JobListingDetailResource;
use App\Http\Resources\JobListingResource;
use App\Models\JobListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class JobListingController extends Controller
{
    /**
     * Get list of active job listings with optional filtering.
     *
     * @param JobListingIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
     *
     * @queryParam category string Filter by category (engineering, sales, marketing, operations, customer_support, finance, human_resources, design)
     * @queryParam department string Filter by department
     * @queryParam location string Filter by location (toronto, vancouver, montreal, remote, hybrid)
     * @queryParam job_type string Filter by job type (full-time, part-time, contract, internship, remote)
     * @queryParam search string Search in title, summary, and description
     * @queryParam page int Page number for pagination
     * @queryParam per_page int Items per page (default: 15, max: 50)
     */
    public function index(JobListingIndexRequest $request)
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 15;
        $category = $validated['category'] ?? null;
        $department = $validated['department'] ?? null;
        $location = $validated['location'] ?? null;
        $jobType = $validated['job_type'] ?? null;
        $search = $validated['search'] ?? null;

        try {
            $query = JobListing::active()
                ->select([
                    'id', 'slug', 'title', 'department', 'location',
                    'job_type', 'category', 'summary', 'salary_range',
                    'posted_at', 'deadline', 'is_featured', 'is_active'
                ]);

            // Apply filters
            if ($category) {
                $query->byCategory($category);
            }
            if ($department) {
                $query->byDepartment($department);
            }
            if ($location) {
                $query->byLocation($location);
            }
            if ($jobType) {
                $query->byType($jobType);
            }
            if ($search) {
                $query->search($search);
            }

            // Order by featured first, then by posted_at desc
            $jobs = $query
                ->orderByDesc('is_featured')
                ->orderByDesc('posted_at')
                ->paginate($perPage);

            return JobListingResource::collection($jobs);
        } catch (\Exception $e) {
            Log::error('Failed to fetch job listings', [
                'error' => $e->getMessage(),
                'filters' => $validated,
            ]);

            return response()->json([
                'message' => 'Failed to fetch job listings.',
            ], 500);
        }
    }

    /**
     * Get a single job listing by slug with full details.
     *
     * @param string $slug
     * @return JobListingDetailResource|JsonResponse
     *
     * @urlParam slug string required The job listing slug. Example: senior-led-engineer-abc123
     */
    public function show(string $slug)
    {
        try {
            $job = Cache::remember("job.{$slug}", 3600, function () use ($slug) {
                return JobListing::active()
                    ->where('slug', $slug)
                    ->first();
            });

            if (!$job) {
                return response()->json([
                    'message' => 'Job listing not found.',
                ], 404);
            }

            return new JobListingDetailResource($job);
        } catch (\Exception $e) {
            Log::error('Failed to fetch job listing detail', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch job listing details.',
            ], 500);
        }
    }

    /**
     * Get available filter options for job listings.
     *
     * @return JsonResponse
     */
    public function filters(): JsonResponse
    {
        try {
            $categories = JobListing::active()
                ->distinct()
                ->pluck('category')
                ->mapWithKeys(fn ($cat) => [$cat => \App\Enums\JobCategory::tryFrom($cat)?->label() ?? $cat]);

            $locations = JobListing::active()
                ->distinct()
                ->pluck('location')
                ->mapWithKeys(fn ($loc) => [$loc => \App\Enums\JobLocation::tryFrom($loc)?->label() ?? $loc]);

            $jobTypes = JobListing::active()
                ->distinct()
                ->pluck('job_type')
                ->mapWithKeys(fn ($type) => [$type => \App\Enums\JobType::tryFrom($type)?->label() ?? $type]);

            $departments = JobListing::active()
                ->distinct()
                ->orderBy('department')
                ->pluck('department');

            return response()->json([
                'data' => [
                    'categories' => $categories,
                    'locations' => $locations,
                    'jobTypes' => $jobTypes,
                    'departments' => $departments,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch job filters', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to fetch job filters.',
            ], 500);
        }
    }
}
