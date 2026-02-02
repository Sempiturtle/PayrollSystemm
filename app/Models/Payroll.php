<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'total_hours',
        'late_minutes',
        'total_deductions',
        'gross_pay',
        'net_pay',
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_hours' => 'decimal:2',
        'late_minutes' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
