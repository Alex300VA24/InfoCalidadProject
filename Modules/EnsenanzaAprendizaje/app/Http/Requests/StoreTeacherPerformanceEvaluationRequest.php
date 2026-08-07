<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\TeacherPerformanceEvaluation;

class StoreTeacherPerformanceEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('execution');
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:users,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:20'],
            'source' => ['required', Rule::in(array_keys(TeacherPerformanceEvaluation::SOURCES))],
            'observations' => ['nullable', 'string', 'max:2000'],
            'evaluated_at' => ['nullable', 'date'],
        ];
    }
}
