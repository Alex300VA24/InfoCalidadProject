<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\AcademicTutoring;

class StoreAcademicTutoringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('tutoring');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'tutor_id' => ['nullable', 'exists:users,id'],
            'tutoring_date' => ['required', 'date'],
            'type' => ['required', Rule::in(array_keys(AcademicTutoring::TYPES))],
            'reason' => ['nullable', 'string', 'max:1000'],
            'outcome' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['pendiente', 'atendida', 'cancelada'])],
        ];
    }
}
