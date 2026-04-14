<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'name',
        'date',
        'type',
        'is_paid',
        'is_double_pay',
        'description',
    ];

    protected $appends = ['pay_option'];

    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
        'is_double_pay' => 'boolean',
    ];

    public function getPayOptionAttribute()
    {
        if ($this->is_double_pay) return 'double';
        if ($this->is_paid) return 'paid';
        return 'unpaid';
    }
}
