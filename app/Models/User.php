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
