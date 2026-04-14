<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'date'    => ['required', 'date'],
            'time_in' => ['required', 'date_format:H:i:s'],
            'time_out' => ['nullable', 'date_format:H:i:s', 'after:time_in'],
            'status'  => ['required', 'in:On-time,Late'],
            'source'  => ['nullable', 'string'],
        ];
    }
}
