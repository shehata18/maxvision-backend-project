<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Submit a job application.
     *
     * @param JobApplicationRequest $request
     * @return JsonResponse
     */
    public function store(JobApplicationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Find job listing if specified
            $jobListing = null;
            if (!empty($validated['job_id'])) {
                $jobListing = JobListing::active()->where('slug', $validated['job_id'])->first();
                if (!$jobListing) {
                    return response()->json([
                        'message' => 'Job listing not found or no longer available.',
                    ], 404);
                }
            }

            // Handle resume upload
            $resumePath = null;
            $resumeOriginalName = null;
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $resumeOriginalName = $file->getClientOriginalName();
                $resumePath = $file->store('resumes', 'public');
            }

            // Create application
            $application = JobApplication::create([
                'job_listing_id' => $jobListing?->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'resume_path' => $resumePath,
                'resume_original_name' => $resumeOriginalName,
                'linkedin_url' => $validated['linkedin_url'] ?? null,
                'portfolio_url' => $validated['portfolio_url'] ?? null,
                'is_general_application' => empty($validated['job_id']) || ($validated['is_general'] ?? false),
                'status' => 'pending', // Explicitly set default status
            ]);

            Log::info('Job application submitted', [
                'application_id' => $application->id,
                'job_id' => $jobListing?->id,
                'email' => $validated['email'],
                'is_general' => $application->is_general_application,
            ]);

            return response()->json([
                'message' => 'Application submitted successfully!',
                'data' => new JobApplicationResource($application),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to submit job application', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to submit application. Please try again.',
            ], 500);
        }
    }

    /**
     * Download resume file.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
     */
    public function downloadResume(int $id)
    {
        try {
            $application = JobApplication::findOrFail($id);

            if (!$application->resume_path) {
                return response()->json([
                    'message' => 'No resume found for this application.',
                ], 404);
            }

            $path = Storage::disk('public')->path($application->resume_path);
            
            if (!file_exists($path)) {
                return response()->json([
                    'message' => 'Resume file not found.',
                ], 404);
            }

            return response()->download($path, $application->resume_original_name ?? 'resume.pdf');

        } catch (\Exception $e) {
            Log::error('Failed to download resume', [
                'application_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to download resume.',
            ], 500);
        }
    }
}
