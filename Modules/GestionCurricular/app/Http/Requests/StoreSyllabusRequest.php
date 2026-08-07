<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSyllabusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user?->hasRole('secretaria') || $user?->hasRole('docente');
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'teacher_id' => 'required|exists:users,id',
            'file' => 'required|file|mimes:pdf|max:20480',
        ];
    }
}
