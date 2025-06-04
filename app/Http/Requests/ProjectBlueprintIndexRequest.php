<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectBlueprintIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => (int) $this->input('page', 1),
            'limit' => (int) $this->input('limit', 20),
            'filter' => array_merge([
                'name' => '',
                'description' => '',
                'status' => '',
            ], $this->input('filter', [])),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'filter.name' => ['sometimes', 'nullable', 'string'],
            'filter.description' => ['sometimes', 'nullable', 'string'],
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }
}
