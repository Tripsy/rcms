<?php

namespace App\Http\Requests;

use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlueprintComponentIndexRequest extends FormRequest
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
                'status' => $this->filter['status'] ?? '',
                'component_type' => $this->filter['component_type'] ?? '',
                'component_format' => $this->filter['component_format'] ?? '',
                'is_required' => $this->filter['is_required'] ?? '',
                'name' => $this->filter['name'] ?? '',
                'description' => $this->filter['description'] ?? '',
                'info' => $this->filter['info'] ?? '',
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
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
            'filter.component_type' => ['sometimes', Rule::enum(BlueprintComponentType::class)],
            'filter.component_format' => ['sometimes', Rule::enum(BlueprintComponentType::class)],
            'filter.is_required' => ['sometimes', Rule::enum(DefaultOption::class)],
            'filter.name' => ['sometimes', 'nullable', 'string'],
            'filter.description' => ['sometimes', 'nullable', 'string'],
            'filter.info' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
