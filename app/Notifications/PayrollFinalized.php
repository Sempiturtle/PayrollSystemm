<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollFinalized extends Notification
{
    use Queueable;

    protected $payroll;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
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
            'type' => 'payroll_finalized',
            'title' => 'Fiscal Disbursement Verified',
            'message' => "Your payroll for period ending {$this->payroll->period_end->format('M d, Y')} has been verified.",
            'amount' => $this->payroll->net_pay,
            'payroll_id' => $this->payroll->id,
            'action_url' => route('dashboard'),
        ];
    }
}
