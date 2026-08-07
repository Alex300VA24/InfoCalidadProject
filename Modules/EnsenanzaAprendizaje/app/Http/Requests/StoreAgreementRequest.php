<?php

namespace Modules\EnsenanzaAprendizaje\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\EnsenanzaAprendizaje\Models\Agreement;

class StoreAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('mobility');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'institution' => ['required', 'string', 'max:200'],
            'type' => ['required', Rule::in(array_keys(Agreement::TYPES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(Agreement::STATUSES))],
            'document_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
