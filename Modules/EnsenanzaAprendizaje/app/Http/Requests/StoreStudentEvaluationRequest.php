<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\StudentEvaluation;

class StoreStudentEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('evaluations');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'evaluation_type' => ['required', Rule::in(array_keys(StudentEvaluation::TYPES))],
            'score' => ['required', 'numeric', 'min:0', 'max:20'],
            'evaluation_date' => ['required', 'date'],
            'observations' => ['nullable', 'string', 'max:500'],
        ];
    }
}
