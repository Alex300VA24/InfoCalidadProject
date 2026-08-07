<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\ClassSession;

class StoreClassSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('execution');
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'topic' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:12'],
            'session_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(array_keys(ClassSession::STATUSES))],
        ];
    }
}
