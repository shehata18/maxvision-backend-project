<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSubmissionStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected ContactSubmission $submission
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
        $statusLabel = $this->submission->status->getLabel();

        return (new MailMessage)
            ->subject('Your MaxVision Inquiry - Status Update')
            ->greeting('Hello ' . $this->submission->first_name . ',')
            ->line('We wanted to let you know that the status of your inquiry has been updated to: **' . $statusLabel . '**.')
            ->line('**Project Type:** ' . $this->submission->project_type)
            ->line('**Timeline:** ' . $this->submission->timeline)
            ->line('A member of our team will be in touch with you shortly regarding the next steps.')
            ->line('If you have any questions in the meantime, feel free to reply to this email or call us at 1-888-LED-PROS.')
            ->salutation('Best regards, The MaxVision Display Team');
    }
}
