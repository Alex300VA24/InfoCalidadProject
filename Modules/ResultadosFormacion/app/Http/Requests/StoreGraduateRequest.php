<?php

namespace Modules\ResultadosFormacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ResultadosFormacion\Models\Graduate;

class StoreGraduateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('graduates');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'graduation_date' => ['nullable', 'date'],
            'work_status' => ['required', Rule::in(array_keys(Graduate::WORK_STATUSES))],
            'employer' => ['nullable', 'string', 'max:255'],
            'job_position' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'survey_date' => ['nullable', 'date'],
            'employment_relationship' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
