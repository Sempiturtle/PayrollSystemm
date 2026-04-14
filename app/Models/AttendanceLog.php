<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use \App\Traits\LogsActivity;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // ──────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('date', $date);
    }
}
