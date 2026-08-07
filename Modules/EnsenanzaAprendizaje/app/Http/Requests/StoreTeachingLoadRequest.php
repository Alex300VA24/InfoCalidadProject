<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\TeachingLoad;

class StoreTeachingLoadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('execution');
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'section' => ['nullable', 'string', 'max:20'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:100'],
            'status' => ['required', Rule::in(array_keys(TeachingLoad::STATUSES))],
        ];
    }
}
