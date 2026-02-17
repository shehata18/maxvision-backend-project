<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected ContactSubmission $submission
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
            ->subject('Thank you for contacting MaxVision Display')
            ->greeting("Hello {$this->submission->first_name},")
            ->line('Thank you for your interest in MaxVision LED displays.')
            ->line("We have received your quote request for: **{$this->submission->project_type}**")
            ->line('Our sales engineering team will review your requirements and get back to you within 24 hours.')
            ->line('**Your Request Details:**')
            ->line("• Project Type: {$this->submission->project_type}")
            ->line("• Timeline: {$this->submission->timeline}")
            ->line("• Budget Range: {$this->submission->budget_range}")
            ->salutation('Best regards, The MaxVision Team');
    }
}
