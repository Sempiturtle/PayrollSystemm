<?php

namespace App\Imports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProfessorScheduleImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Map each row to a Schedule model for this specific professor.
     */
    public function model(array $row)
    {
        return new Schedule([
            'user_id'     => $this->userId,
            'day_of_week' => $row['day_of_week'],
            'start_time'  => $row['start_time'],
            'end_time'    => $row['end_time'],
            'effective_from' => $row['effective_date'] ?? null,
        ]);
    }

    /**
     * Normalize day abbreviations to full day names before validation.
     */
    public function prepareForValidation($data, $index)
    {
        $dayMapping = [
            'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday',
            'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday',
            'm' => 'Monday', 't' => 'Tuesday', 'w' => 'Wednesday', 'th' => 'Thursday', 'f' => 'Friday',
        ];

        if (isset($data['day_of_week'])) {
            $day = strtolower(trim($data['day_of_week']));
            $data['day_of_week'] = $dayMapping[$day] ?? ucfirst($day);
        }

        // Transform numeric or string time to H:i:s
        if (isset($data['start_time'])) {
            $data['start_time'] = $this->transformTime($data['start_time']);
        }
        if (isset($data['end_time'])) {
            $data['end_time'] = $this->transformTime($data['end_time']);
        }

        return $data;
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required',
            'end_time'    => 'required',
        ];
    }

    /**
     * Convert Excel's numeric time format or string time to a database-friendly H:i:s format.
     * Includes a "Smart PM" heuristic: if a time between 1:00 and 6:59 is provided
     * without explicit AM/PM, it is assumed to be PM.
     */
    private function transformTime($value)
    {
        if (empty($value)) return null;

        // If it's a numeric value from Excel (fraction of 24h)
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('H:i:s');
            } catch (\Exception $e) {
                return $value;
            }
        }

        // If it's already a string, check for explicit AM/PM
        $stringValue = (string) $value;
        $hasAmPm = preg_match('/[ap]m/i', $stringValue);

        try {
            $time = \Carbon\Carbon::parse($stringValue);
            
            // Heuristic for shorthand like "2:00" in a daytime work context
            // If hour is 1-6 and AM/PM wasn't explicitly specified in the string
            if (!$hasAmPm && $time->hour >= 1 && $time->hour <= 6) {
                $time->addHours(12);
            }

            return $time->format('H:i:s');
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function customValidationMessages()
    {
        return [
            'day_of_week.in' => 'Day ":input" is invalid. Use full name (Monday) or abbreviation (Mon).',
        ];
    }
}
