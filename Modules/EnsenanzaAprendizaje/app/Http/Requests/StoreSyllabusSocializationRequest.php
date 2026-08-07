<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSyllabusSocializationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('execution');
    }

    public function rules(): array
    {
        return [
            'syllabus_id' => ['required', 'exists:syllabi,id'],
            'date' => ['required', 'date'],
            'evidence_path' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'registered_by' => ['nullable', 'exists:users,id'],
        ];
    }
}
