<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'effective_from',
    ];

    protected $casts = [
        'effective_from' => 'date',
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

    /**
     * Filter schedules for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filter by day of the week.
     */
    public function scopeForDay($query, string $dayName)
    {
        return $query->where('day_of_week', $dayName);
    }

    /**
     * Get schedules ordered by the natural weekday order (Mon → Sun).
     */
    public function scopeOrderByDay($query)
    {
        return $query->orderByRaw("FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')");
    }

    // ──────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────

    /**
     * Human-readable time range e.g. "08:00 AM → 05:00 PM"
     */
    protected function formattedTimeRange(): Attribute
    {
        return Attribute::get(function () {
            $start = \Carbon\Carbon::parse($this->start_time)->format('h:i A');
            $end   = \Carbon\Carbon::parse($this->end_time)->format('h:i A');
            return "{$start} → {$end}";
        });
    }

    /**
     * Total scheduled hours for this day.
     */
    protected function scheduledHours(): Attribute
    {
        return Attribute::get(function () {
            $start = \Carbon\Carbon::parse($this->start_time);
            $end   = \Carbon\Carbon::parse($this->end_time);
            return round($end->diffInMinutes($start) / 60, 1);
        });
    }
}
