<?php

namespace Modules\ResultadosFormacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ResultadosFormacion\Models\DegreeApplication;

class StoreDegreeApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('degrees');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::in(array_keys(DegreeApplication::TYPES))],
            'thesis_title' => ['nullable', 'string', 'max:255'],
            'advisor_id' => ['nullable', 'exists:users,id'],
            'application_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
