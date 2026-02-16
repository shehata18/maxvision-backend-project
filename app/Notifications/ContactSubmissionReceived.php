<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSubmissionReceived extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/admin/contact-submissions/' . $this->submission->id);

        return (new MailMessage)
            ->subject('New Contact Submission from ' . $this->submission->full_name)
            ->greeting('New inquiry received!')
            ->line('**Name:** ' . $this->submission->full_name)
            ->line('**Email:** ' . $this->submission->email)
            ->line('**Company:** ' . ($this->submission->company ?? 'N/A'))
            ->line('**Project Type:** ' . $this->submission->project_type)
            ->line('**Timeline:** ' . $this->submission->timeline)
            ->line('**Budget Range:** ' . $this->submission->budget_range)
            ->action('View Submission', $url)
            ->salutation('MaxVision CMS');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'submission_id' => $this->submission->id,
            'name' => $this->submission->full_name,
            'email' => $this->submission->email,
            'company' => $this->submission->company,
            'project_type' => $this->submission->project_type,
            'timeline' => $this->submission->timeline,
            'budget_range' => $this->submission->budget_range,
        ];
    }
}
