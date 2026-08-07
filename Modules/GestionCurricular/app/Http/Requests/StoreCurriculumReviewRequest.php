<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurriculumReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPresidenteCotejo();
    }

    public function rules(): array
    {
        return [
            'checklist_template_id' => 'required|exists:checklist_templates,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
        ];
    }
}
