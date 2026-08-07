<?php

namespace Modules\GestionCurricular\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isDocente() || $this->user()?->isSecretaria();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'request_type' => 'required|in:bibliographic,hemerographic,equipment',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'documents.*' => 'nullable|file|mimes:pdf|max:10240',
            'attachments.*' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }
}
