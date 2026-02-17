<?php

namespace App\Observers;

use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\ContactConfirmationNotification;
use App\Notifications\ContactSubmissionReceived;
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
}
