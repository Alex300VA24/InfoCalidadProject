<?php

namespace Modules\ResultadosFormacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ResultadosFormacion\Models\DegreeCommitteeAct;

class StoreDegreeCommitteeActRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('degrees');
    }

    public function rules(): array
    {
        return [
            'degree_application_id' => ['nullable', 'exists:degree_applications,id'],
            'act_type' => ['required', Rule::in(array_keys(DegreeCommitteeAct::ACT_TYPES))],
            'session_date' => ['nullable', 'date'],
            'result' => ['nullable', Rule::in(array_keys(DegreeCommitteeAct::RESULTS))],
            'score' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'pdf_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
