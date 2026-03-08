<?php

namespace App\Observers;

use App\Models\ContactSubmission;
use App\Models\Setting;
use App\Notifications\ContactConfirmationNotification;
use App\Notifications\ContactSubmissionReceived;
use App\Notifications\ContactSubmissionStatusChanged;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ContactSubmissionObserver
{
    /**
     * Handle the ContactSubmission "created" event.
     */
    public function created(ContactSubmission $contactSubmission): void
    {
        // Log the submission
        Log::info('New contact submission received', [
            'id'           => $contactSubmission->id,
            'name'         => $contactSubmission->full_name,
            'email'        => $contactSubmission->email,
            'project_type' => $contactSubmission->project_type,
        ]);

        // ── 1) Notify company contact_email from Settings ────────────────────
        try {
            $contactEmail = Setting::get('contact_email');

            if ($contactEmail) {
                Notification::route('mail', $contactEmail)
                    ->notify(new ContactSubmissionReceived($contactSubmission));

                Log::info('Admin notification sent to contact_email', [
                    'submission_id' => $contactSubmission->id,
                    'contact_email' => $contactEmail,
                ]);
            } else {
                Log::warning('No contact_email set in Settings — admin notification skipped', [
                    'submission_id' => $contactSubmission->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send admin notification to contact_email', [
                'submission_id' => $contactSubmission->id,
                'error'         => $e->getMessage(),
            ]);
        }

        // ── 2) Send confirmation email to the customer ───────────────────────
        try {
            $contactSubmission->notify(new ContactConfirmationNotification($contactSubmission));
        } catch (\Exception $e) {
            Log::warning('Failed to send customer confirmation', [
                'submission_id' => $contactSubmission->id,
                'error'         => $e->getMessage(),
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
                'id'         => $contactSubmission->id,
                'old_status' => $contactSubmission->getOriginal('status'),
                'new_status' => $contactSubmission->status->value,
            ]);

            // Send status change notification to the customer
            try {
                $contactSubmission->notify(new ContactSubmissionStatusChanged($contactSubmission));
            } catch (\Exception $e) {
                Log::warning('Failed to send status change notification', [
                    'submission_id' => $contactSubmission->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }
    }
}
