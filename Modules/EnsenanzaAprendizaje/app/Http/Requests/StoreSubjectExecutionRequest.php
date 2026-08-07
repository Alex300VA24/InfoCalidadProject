<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\SubjectExecution;

class StoreSubjectExecutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('execution');
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'syllabus_id' => ['nullable', 'exists:syllabi,id'],
            'progress_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(array_keys(SubjectExecution::STATUSES))],
        ];
    }
}
