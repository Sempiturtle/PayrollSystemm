<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DiscrepancyReport extends Model
{
    use \App\Traits\LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payroll_id',
        'description',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
