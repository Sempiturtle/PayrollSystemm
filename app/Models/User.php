<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use \App\Traits\LogsActivity, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'rfid_card_num',
        'fingerprint_slot',           // add this
        'fingerprint_enrolled',     // add this
        'fingerprint_enrolled_at',
        'hourly_rate',
        'monthly_salary',
        'employment_type',
        'role',
        'schedule_file',
        'tin_id',
        'sss_id',
        'philhealth_id',
        'pagibig_id',
        'sick_leave_credits',
        'vacation_leave_credits',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hourly_rate' => 'decimal:2',
            'monthly_salary' => 'decimal:2',
        ];
    }

    // ──────────────────────────────────────
    // Employment Type Helpers
    // ──────────────────────────────────────

    public function isProfessor(): bool
    {
        return $this->employment_type === 'professor';
    }

    public function isStaff(): bool
    {
        return $this->employment_type === 'staff';
    }

    public function isPartTime(): bool
    {
        return $this->employment_type === 'part_time';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function isStaffOrAdmin(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    /**
     * Get the effective hourly rate.
     * Professors: use hourly_rate directly.
     * Staff/Part-time: derive from monthly_salary / total scheduled hours per month.
     */
    public function getEffectiveHourlyRate(): float
    {
        if ($this->isProfessor()) {
            return (float) $this->hourly_rate;
        }

        // Calculate total scheduled hours per week
        $weeklyHours = $this->schedules->sum(function ($schedule) {
            $start = \Carbon\Carbon::parse($schedule->start_time);
            $end = \Carbon\Carbon::parse($schedule->end_time);
            return $end->diffInMinutes($start) / 60;
        });

        // Approximate monthly hours = weekly hours × 4.33 (average weeks per month)
        $monthlyHours = $weeklyHours * 4.33;

        if ($monthlyHours <= 0) {
            return 0;
        }

        return (float) $this->monthly_salary / $monthlyHours;
    }

    // ──────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
