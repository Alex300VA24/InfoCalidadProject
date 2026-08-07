<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPresidenteCotejo();
    }

    public function rules(): array
    {
        return [
            'action_type_id' => 'required|exists:action_types,id',
            'observations' => 'nullable|string|max:2000',
        ];
    }
}
