<?php

namespace Modules\GestionIngreso\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('personal_matricula');
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'career_id' => 'required|exists:careers,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'matricula_fee' => 'nullable|numeric|min:0',
        ];
    }
}
