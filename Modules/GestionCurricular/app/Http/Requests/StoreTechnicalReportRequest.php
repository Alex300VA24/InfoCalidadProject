<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTechnicalReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPresidenteCotejo();
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }
}
