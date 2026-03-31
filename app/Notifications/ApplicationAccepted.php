<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationAccepted extends Notification
{
    use Queueable;

    protected $job; // Simpan data job biar bisa dipake di notif

    /**
     * Create a new notification instance.
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Simpan di database biar bisa ditampilin di navbar
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Selamat! Lamaran kamu untuk job "' . $this->job->title . '" telah diterima.')
            ->action('Lihat Job', url('/workspace/' . $this->job->id))
            ->line('Silakan mulai kerjanya!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'message' => 'Lamaran kamu untuk job "' . $this->job->title . '" telah diterima!',
            'url' => '/workspace/' . $this->job->id,
        ];
    }
}
