<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PipelineCompletedNotification extends Notification
{
    use Queueable;

    public int $datasetId;
    public string $stage;
    public int $recordsProcessed;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $datasetId, string $stage, int $recordsProcessed)
    {
        $this->datasetId = $datasetId;
        $this->stage = $stage;
        $this->recordsProcessed = $recordsProcessed;
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
            'type' => 'pipeline_completed',
            'title' => "{$this->stage} Pipeline Completed",
            'message' => "Successfully processed {$this->recordsProcessed} records for Dataset ID {$this->datasetId}.",
            'dataset_id' => $this->datasetId,
            'icon' => 'check-circle',
            'color' => 'green'
        ];
    }
}
