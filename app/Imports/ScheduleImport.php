<?php

namespace App\Imports;

use App\Models\Schedule;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ScheduleImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = User::where('employee_id', $row['employee_id'])->first();

        if (!$user) return null;

        // Use updateOrCreate to handle existing schedules for the same day
        return Schedule::updateOrCreate(
            [
                'user_id' => $user->id,
                'day_of_week' => $row['day_of_week'],
            ],
            [
                'start_time'     => $row['start_time'],
                'end_time'       => $row['end_time'],
                'effective_from' => $row['effective_date'] ?? null,
            ]
        );
    }

    public function prepareForValidation($data, $index)
    {
        $dayMapping = [
            'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 
            'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday',
            'm' => 'Monday', 't' => 'Tuesday', 'w' => 'Wednesday', 'th' => 'Thursday', 'f' => 'Friday'
        ];

        if (isset($data['day_of_week'])) {
            $day = strtolower(trim($data['day_of_week']));
            if (isset($dayMapping[$day])) {
                $data['day_of_week'] = $dayMapping[$day];
            } else {
                $data['day_of_week'] = ucfirst($day);
            }
        }

        return $data;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:users,employee_id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.exists' => 'Employee ID :input not found in our records.',
            'day_of_week.in' => 'Day invalid. Must be full name (e.g., Monday) or common abbreviation.',
        ];
    }
}
