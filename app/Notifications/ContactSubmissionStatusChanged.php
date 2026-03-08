<?php

namespace App\Notifications;

use App\Enums\ContactSubmissionStatus;
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
        $statusEmoji = $this->getStatusEmoji($this->submission->status);
        $statusMessage = $this->getStatusMessage($this->submission->status);

        return (new MailMessage)
            ->subject("Update on Your MaxVision Inquiry - {$statusLabel}")
            ->greeting("Hello {$this->submission->first_name}! 👋")
            ->line("We have an update regarding your LED display inquiry.")
            ->line('')
            ->line("### {$statusEmoji} Status Update: **{$statusLabel}**")
            ->line($statusMessage)
            ->line('')
            ->line('### 📋 Your Project Details')
            ->line("**Project Type:** {$this->submission->project_type}")
            ->line("**Timeline:** {$this->submission->timeline}")
            ->line("**Budget Range:** {$this->submission->budget_range}")
            ->line('')
            ->when($this->submission->status === ContactSubmissionStatus::CONTACTED, function ($mail) {
                return $mail->line('### 📞 Next Steps')
                    ->line('• Check your email for our detailed proposal')
                    ->line('• Review the technical specifications we\'ve prepared')
                    ->line('• Schedule a follow-up call if you have questions')
                    ->line('');
            })
            ->when($this->submission->status === ContactSubmissionStatus::CONVERTED, function ($mail) {
                return $mail->line('### 🎉 Welcome to MaxVision!')
                    ->line('We\'re excited to work with you on this project.')
                    ->line('• Your dedicated project manager will contact you soon')
                    ->line('• You\'ll receive a detailed project timeline')
                    ->line('• Our engineering team is ready to begin')
                    ->line('');
            })
            ->line('If you have any questions, feel free to reply to this email or call us at **1-888-LED-PROS**.')
            ->salutation("Best regards,\n\n**The MaxVision Display Team**\n\n*Engineering Brilliance in LED Technology*");
    }

    /**
     * Get emoji for status
     */
    protected function getStatusEmoji(ContactSubmissionStatus $status): string
    {
        return match ($status) {
            ContactSubmissionStatus::NEW => '📨',
            ContactSubmissionStatus::CONTACTED => '✅',
            ContactSubmissionStatus::CONVERTED => '🎉',
        };
    }

    /**
     * Get message for status
     */
    protected function getStatusMessage(ContactSubmissionStatus $status): string
    {
        return match ($status) {
            ContactSubmissionStatus::NEW => 'Your inquiry has been received and is being reviewed by our team.',
            ContactSubmissionStatus::CONTACTED => 'Great news! Our sales engineering team has reviewed your requirements and prepared a customized proposal for you.',
            ContactSubmissionStatus::CONVERTED => 'Congratulations! We\'re thrilled to officially welcome you as a MaxVision client. Your project is now moving into the implementation phase.',
        };
    }
}
