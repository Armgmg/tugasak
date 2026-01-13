<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScanStatusUpdated extends Notification
{
    use Queueable;

    protected $scan;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($scan, $status)
    {
        $this->scan = $scan;
        $this->status = $status;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'scan_id' => $this->scan->id,
            'status' => $this->status,
            'message' => "Scan sampah Anda telah " . ($this->status == 'approved' ? 'disetujui' : 'ditolak') . ".",
            'points' => $this->scan->ai_result['points'] ?? 0, // Assuming structure
            'created_at' => now(),
        ];
    }
}
