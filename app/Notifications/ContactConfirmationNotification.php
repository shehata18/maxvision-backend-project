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
            ->subject('Thank You for Contacting MaxVision Display')
            ->greeting("Hello {$this->submission->first_name}! 👋")
            ->line('Thank you for your interest in **MaxVision LED Display Solutions**.')
            ->line('')
            ->line("We've received your quote request for **{$this->submission->project_type}** and our sales engineering team is already reviewing your requirements.")
            ->line('')
            ->line('### 📋 Your Request Summary')
            ->line("**Project Type:** {$this->submission->project_type}")
            ->line("**Timeline:** {$this->submission->timeline}")
            ->line("**Size Requirements:** {$this->submission->size_requirements}")
            ->line("**Budget Range:** {$this->submission->budget_range}")
            ->line('')
            ->line('### ⏱️ What Happens Next?')
            ->line('• Our team will review your specifications within **24 hours**')
            ->line('• You\'ll receive a detailed proposal tailored to your needs')
            ->line('• We\'ll schedule a consultation call at your convenience')
            ->line('')
            ->line('In the meantime, feel free to explore our case studies and product catalog on our website.')
            ->line('')
            ->line('If you have any urgent questions, reply to this email or call us at **1-888-LED-PROS**.')
            ->salutation("Best regards,\n\n**The MaxVision Display Team**\n\n*Engineering Brilliance in LED Technology*");
    }
}
