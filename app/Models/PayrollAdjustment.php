<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollAdjustment extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'payroll_id',
        'user_id',
        'type',
        'description',
        'amount',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if this adjustment adds to the employee's pay.
     */
    public function isBonus(): bool
    {
        return $this->type === 'bonus';
    }

    /**
     * Check if this adjustment subtracts from the employee's pay.
     */
    public function isDeduction(): bool
    {
        return $this->type === 'deduction';
    }

    /**
     * Get the signed amount (positive for bonus, negative for deduction).
     */
    public function getSignedAmountAttribute(): float
    {
        return $this->isBonus() ? $this->amount : -$this->amount;
    }
}
