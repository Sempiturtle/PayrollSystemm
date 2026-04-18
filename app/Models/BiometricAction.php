<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricAction extends Model
{
    protected $fillable = [
        'user_id',
        'command',
        'status',
        'fingerprint_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
