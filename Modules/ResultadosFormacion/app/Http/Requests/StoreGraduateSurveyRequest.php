<?php

namespace Modules\ResultadosFormacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGraduateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('graduates');
    }

    public function rules(): array
    {
        return [
            'graduate_id' => ['nullable', 'exists:graduates,id'],
            'period' => ['required', 'string', 'max:20'],
            'survey_date' => ['required', 'date'],
            'employed' => ['sometimes', 'boolean'],
            'job_related_to_career' => ['nullable', 'boolean'],
            'competency_level_score' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'income' => ['nullable', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
