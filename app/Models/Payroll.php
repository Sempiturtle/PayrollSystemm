<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'total_hours',
        'late_minutes',
        'late_deduction',
        'undertime_deduction',
        'sss_deduction',
        'philhealth_deduction',
        'pagibig_deduction',
        'tax_deduction',
        'sss_employer',
        'philhealth_employer',
        'pagibig_employer',
        'sss_ec',
        'total_deductions',
        'gross_pay',
        'net_pay',
        'status',
        'calculation_snapshot',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'calculation_snapshot' => 'array',
        'total_hours' => 'decimal:2',
        'late_minutes' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'undertime_deduction' => 'decimal:2',
        'sss_deduction' => 'decimal:2',
        'philhealth_deduction' => 'decimal:2',
        'pagibig_deduction' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'sss_employer' => 'decimal:2',
        'philhealth_employer' => 'decimal:2',
        'pagibig_employer' => 'decimal:2',
        'sss_ec' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a digital fingerprint for fiscal verification.
     */
    public function getVerificationFingerprint(): string
    {
        return hash_hmac('sha256', 
            $this->id . $this->user_id . $this->net_pay . $this->period_end->toDateString(), 
            config('app.key')
        );
    }
}
