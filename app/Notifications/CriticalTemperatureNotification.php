<?php

namespace App\Notifications;

use App\Models\TemperatureAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CriticalTemperatureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TemperatureAlert $alert
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'refrigerator_id' => $this->alert->refrigerator_id,
            'message' => $this->alert->message,
            'started_at' => $this->alert->started_at?->toDateTimeString(),
        ];
        
    }

     public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Critical Refrigerator Temperature Alert')
            ->line($this->alert->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
