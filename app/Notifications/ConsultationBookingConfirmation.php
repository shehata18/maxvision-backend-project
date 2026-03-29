<?php

namespace App\Notifications;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationBookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected ConsultationBooking $booking
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $siteName = config('app.name', 'Maxvision Display');

        return (new MailMessage)
            ->subject('Consultation Booking Confirmation - ' . $siteName)
            ->greeting('Hello ' . $this->booking->first_name . ',')
            ->line('Thank you for booking a consultation with us!')
            ->line('')
            ->line('We have received your consultation request with the following details:')
            ->line('')
            ->line('**Appointment Details:**')
            ->line("**Date:** {$this->booking->preferred_date->format('l, F j, Y')}")
            ->line("**Time:** {$this->booking->preferred_time}")
            ->line('')
            ->line('Our team will contact you within 24 hours to confirm your appointment and discuss your LED display needs.')
            ->line('')
            ->line('If you need to reschedule or have any questions, please don\'t hesitate to contact us.')
            ->line('')
            ->line('We look forward to speaking with you!')
            ->salutation('Best regards, The ' . $siteName . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'preferred_date' => $this->booking->preferred_date->format('Y-m-d'),
            'preferred_time' => $this->booking->preferred_time,
        ];
    }
}
