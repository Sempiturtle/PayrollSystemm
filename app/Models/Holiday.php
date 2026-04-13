<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'name',
        'date',
        'type',
        'is_paid',
        'is_double_pay',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
        'is_double_pay' => 'boolean',
    ];
}
