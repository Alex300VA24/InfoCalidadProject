<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\RemedialProgram;

class StoreRemedialProgramRequest extends FormRequest
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
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'plan_path' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(array_keys(RemedialProgram::STATUSES))],
        ];
    }
}
