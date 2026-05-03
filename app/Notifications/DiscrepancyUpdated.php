<?php

namespace App\Notifications;

use App\Models\DiscrepancyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DiscrepancyUpdated extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(DiscrepancyReport $report)
    {
        $this->report = $report;
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
            'type' => 'discrepancy_updated',
            'title' => 'Conflict Disclosure Updated',
            'message' => "Your discrepancy report for disbursement #{$this->report->payroll_id} has been updated to: {$this->report->status}.",
            'status' => $this->report->status,
            'report_id' => $this->report->id,
            'action_url' => route('dashboard'),
        ];
    }
}
