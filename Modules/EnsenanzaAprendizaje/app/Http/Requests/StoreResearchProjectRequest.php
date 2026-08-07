<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\ResearchProject;

class StoreResearchProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('research');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'advisor_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'area' => ['nullable', 'string', 'max:255'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(ResearchProject::STATUSES))],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }
}
