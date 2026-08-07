<?php

namespace Modules\GestionIngreso\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('coordinador_admision');
    }

    public function rules(): array
    {
        return [
            'admission_process_id' => 'required|exists:admission_processes,id',
            'dni' => 'required|string|max:15',
            'paterno' => 'required|string|max:100',
            'materno' => 'nullable|string|max:100',
            'nombres' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'career_id' => 'required|exists:careers,id',
        ];
    }
}
