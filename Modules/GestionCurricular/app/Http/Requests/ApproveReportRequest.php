<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isDirectorEscuela();
    }

    public function rules(): array
    {
        return [
            'decision' => 'required|in:approved,observed',
            'comments' => 'nullable|string|max:2000',
        ];
    }
}
