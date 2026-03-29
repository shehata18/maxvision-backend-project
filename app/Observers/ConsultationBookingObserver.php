<?php

namespace App\Observers;

use App\Models\ConsultationBooking;
use App\Models\Setting;
use App\Notifications\ConsultationBookingConfirmation;
use App\Notifications\ConsultationBookingReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ConsultationBookingObserver
{
    /**
     * Handle the ConsultationBooking "created" event.
     */
    public function created(ConsultationBooking $booking): void
    {
        // Log the booking
        Log::info('New consultation booking received', [
            'id' => $booking->id,
            'name' => $booking->full_name,
            'email' => $booking->email,
            'date' => $booking->preferred_date->format('Y-m-d'),
            'time' => $booking->preferred_time,
        ]);

        // ── 1) Send notification to admin ───────────────────────
        try {
            $contactEmail = Setting::get('contact_email');
            
            if ($contactEmail) {
                Notification::route('mail', $contactEmail)
                    ->notify(new ConsultationBookingReceived($booking));

                Log::info('Admin notification sent for consultation booking', [
                    'booking_id' => $booking->id,
                    'contact_email' => $contactEmail,
                ]);
            } else {
                Log::warning('No contact_email set in Settings — admin notification skipped', [
                    'booking_id' => $booking->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send admin notification for consultation booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        // ── 2) Send confirmation email to the customer ───────────────────────
        try {
            $booking->notify(new ConsultationBookingConfirmation($booking));
            
            Log::info('Customer confirmation sent for consultation booking', [
                'booking_id' => $booking->id,
                'customer_email' => $booking->email,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to send customer confirmation for consultation booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the ConsultationBooking "updated" event.
     */
    public function updated(ConsultationBooking $booking): void
    {
        // Check if status was changed
        if ($booking->isDirty('status')) {
            Log::info('Consultation booking status changed', [
                'id' => $booking->id,
                'old_status' => $booking->getOriginal('status'),
                'new_status' => $booking->status->value,
            ]);
        }
    }
}
