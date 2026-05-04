<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Substitution extends Model
{
    use SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'absent_user_id',
        'relief_user_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'bonus_amount',
        'adjustment_id',
    ];

    protected $casts = [
        'date' => 'date',
        'bonus_amount' => 'decimal:2',
    ];

    public function absentUser()
    {
        return $this->belongsTo(User::class, 'absent_user_id');
    }

    public function reliefUser()
    {
        return $this->belongsTo(User::class, 'relief_user_id');
    }

    public function adjustment()
    {
        return $this->belongsTo(PayrollAdjustment::class, 'adjustment_id');
    }
}
