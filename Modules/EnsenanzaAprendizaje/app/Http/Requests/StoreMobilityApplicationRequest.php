<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\MobilityApplication;

class StoreMobilityApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('mobility');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'type' => ['required', Rule::in(array_keys(MobilityApplication::TYPES))],
            'destination_institution' => ['nullable', 'string', 'max:255'],
            'program_name' => ['nullable', 'string', 'max:255'],
            'scholarship_name' => ['nullable', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(MobilityApplication::STATUSES))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
