<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContactSubmissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Store a new contact/quote submission.
     *
     * Creates a ContactSubmission record. The ContactSubmissionObserver
     * automatically handles sending admin and customer notifications.
     *
     * @param ContactRequest $request
     * @return JsonResponse
     *
     * @response 201 { "message": "Quote request submitted successfully", "submission_id": 123 }
     * @response 422 { "message": "The given data was invalid.", "errors": { ... } }
     * @response 429 { "message": "Too many quote requests. Please try again later." }
     * @response 500 { "message": "Failed to submit quote request." }
     */
    public function store(ContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Remove honeypot field before creating record
            unset($validated['honeypot']);

            $submission = ContactSubmission::create(array_merge($validated, [
                'status' => ContactSubmissionStatus::NEW,
            ]));

            return response()->json([
                'message' => 'Quote request submitted successfully.',
                'submission_id' => $submission->id,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to submit contact form', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
            ]);

            return response()->json([
                'message' => 'Failed to submit quote request.',
            ], 500);
        }
    }
}
