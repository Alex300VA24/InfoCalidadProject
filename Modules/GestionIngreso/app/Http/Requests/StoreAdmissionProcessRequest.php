<?php

namespace Modules\GestionIngreso\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('coordinador_admision');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'career_id' => 'required|exists:careers,id',
            'vacancies' => 'required|integer|min:0',
            'modality' => 'required|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:borrador,activo,cerrado',
        ];
    }
}
