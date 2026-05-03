<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, \App\Traits\LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'employee_id',
        'rfid_card_num',
        'fingerprint_id',
        'biometric_template',
        'hourly_rate',
        'role',
        'schedule_file',
        'schedule_image',
        'tin_id',
        'sss_id',
        'philhealth_id',
        'pagibig_id',
        'sick_leave_credits',
        'vacation_leave_credits',
    ];

    /**
     * Required fields for a complete profile.
     */
    public const REQUIRED_PROFILE_FIELDS = [
        'employee_id' => 'Employee ID',
        'tin_id' => 'TIN ID',
        'sss_id' => 'SSS ID',
        'philhealth_id' => 'PhilHealth ID',
        'pagibig_id' => 'Pag-IBIG ID',
    ];

    /**
     * Calculate profile completion percentage.
     */
    public function getProfileCompletionAttribute(): int
    {
        $filledCount = 0;
        foreach (array_keys(self::REQUIRED_PROFILE_FIELDS) as $field) {
            if (!empty($this->{$field})) {
                $filledCount++;
            }
        }

        return (int) round(($filledCount / count(self::REQUIRED_PROFILE_FIELDS)) * 100);
    }

    /**
     * Get list of missing required profile fields.
     */
    public function getMissingProfileFieldsAttribute(): array
    {
        $missing = [];
        foreach (self::REQUIRED_PROFILE_FIELDS as $field => $label) {
            if (empty($this->{$field})) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

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
        ];
    }

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
