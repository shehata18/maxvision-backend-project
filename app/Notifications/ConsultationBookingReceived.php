<?php

namespace App\Notifications;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationBookingReceived extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('New Consultation Booking - ' . $this->booking->full_name)
            ->greeting('New Consultation Booking')
            ->line('A new consultation has been booked on your website.')
            ->line('')
            ->line('**Customer Details:**')
            ->line("**Name:** {$this->booking->full_name}")
            ->line("**Email:** {$this->booking->email}")
            ->line("**Phone:** " . ($this->booking->phone ?: 'Not provided'))
            ->line("**Company:** " . ($this->booking->company ?: 'Not provided'))
            ->line('')
            ->line('**Appointment Details:**')
            ->line("**Preferred Date:** {$this->booking->preferred_date->format('l, F j, Y')}")
            ->line("**Preferred Time:** {$this->booking->preferred_time}")
            ->line('')
            ->when($this->booking->message, function ($mail) {
                return $mail
                    ->line('**Additional Notes:**')
                    ->line($this->booking->message)
                    ->line('');
            })
            ->line('Please contact the customer to confirm the appointment.')
            ->action('View in Admin Panel', url('/admin/consultation-bookings'))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->full_name,
            'customer_email' => $this->booking->email,
            'preferred_date' => $this->booking->preferred_date->format('Y-m-d'),
            'preferred_time' => $this->booking->preferred_time,
        ];
    }
}
