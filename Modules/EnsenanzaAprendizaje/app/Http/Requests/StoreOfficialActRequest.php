<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\OfficialAct;

class StoreOfficialActRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('evaluations');
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'pdf_path' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(array_keys(OfficialAct::STATUSES))],
        ];
    }
}
