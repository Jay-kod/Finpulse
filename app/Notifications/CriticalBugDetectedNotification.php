<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CriticalBugDetectedNotification extends Notification
{
    use Queueable;

    public int $reviewId;
    public string $appName;
    public float $sentimentScore;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $reviewId, string $appName, float $sentimentScore)
    {
        $this->reviewId = $reviewId;
        $this->appName = $appName;
        $this->sentimentScore = $sentimentScore;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'critical_bug',
            'title' => "Critical Bug: {$this->appName}",
            'message' => "Extremely negative sentiment ({$this->sentimentScore}) detected on Review ID {$this->reviewId}.",
            'review_id' => $this->reviewId,
            'icon' => 'exclamation-triangle',
            'color' => 'red'
        ];
    }
}
