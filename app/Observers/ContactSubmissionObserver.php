<?php

namespace App\Observers;

use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactConfirmationNotification;
use App\Notifications\ContactSubmissionReceived;
use App\Notifications\ContactSubmissionStatusChanged;
use Illuminate\Support\Facades\Log;

class ContactSubmissionObserver
{
    /**
     * Handle the ContactSubmission "created" event.
     */
    public function created(ContactSubmission $contactSubmission): void
    {
        // Log the submission
        Log::info('New contact submission received', [
            'id' => $contactSubmission->id,
            'name' => $contactSubmission->full_name,
            'email' => $contactSubmission->email,
            'project_type' => $contactSubmission->project_type,
        ]);

        // Notify admin users
        try {
            $admins = User::all();
            foreach ($admins as $admin) {
                $admin->notify(new ContactSubmissionReceived($contactSubmission));
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send admin notification', [
                'submission_id' => $contactSubmission->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Send customer confirmation email
        try {
            $contactSubmission->notify(new ContactConfirmationNotification($contactSubmission));
        } catch (\Exception $e) {
            Log::warning('Failed to send customer confirmation', [
                'submission_id' => $contactSubmission->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the ContactSubmission "updated" event.
     */
    public function updated(ContactSubmission $contactSubmission): void
    {
        // Check if status was changed
        if ($contactSubmission->isDirty('status')) {
            Log::info('Contact submission status changed', [
                'id' => $contactSubmission->id,
                'old_status' => $contactSubmission->getOriginal('status'),
                'new_status' => $contactSubmission->status->value,
            ]);

            // Send status change notification to customer
            try {
                $contactSubmission->notify(new ContactSubmissionStatusChanged($contactSubmission));
            } catch (\Exception $e) {
                Log::warning('Failed to send status change notification', [
                    'submission_id' => $contactSubmission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
