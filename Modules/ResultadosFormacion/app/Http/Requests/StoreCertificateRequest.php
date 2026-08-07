<?php

namespace Modules\ResultadosFormacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ResultadosFormacion\Models\Certificate;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('degrees');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::in(array_keys(Certificate::TYPES))],
            'concept' => ['required', 'string', 'max:500'],
            'issued_at' => ['required', 'date'],
            'issued_by' => ['nullable', 'string', 'max:255'],
        ];
    }
}
