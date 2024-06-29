<?php

namespace App\Http\Requests;

use App\Enums\DefaultOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemContentIndexRequest extends FormRequest
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
            'page' => (int) $this->page ?? 1,
            'limit' => (int) $this->limit ?? 5,
            'filter' => [
                'blueprint_component_id' => $this->filter['blueprint_component_id'] ?? null,
                'is_active' => $this->filter['is_active'] ?? DefaultOption::YES->value,
            ],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer'],
            'limit' => ['required', 'integer', 'max:15'],
            'filter.is_active' => ['sometimes', Rule::enum(DefaultOption::class)],
            'filter.blueprint_component_id' => ['sometimes', 'nullable', 'integer'],
        ];
    }
}
