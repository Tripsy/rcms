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
            'page' => (int) $this->page ?? 1,
            'limit' => (int) $this->limit ?? 5,
            'filter' => [
                'name' => $this->filter['name'] ?? '',
                'description' => $this->filter['description'] ?? '',
                'status' => $this->filter['status'] ?? '',
            ],
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
            'page' => ['required', 'integer'],
            'limit' => ['required', 'integer', 'max:15'],
            'filter.name' => ['sometimes', 'nullable', 'string'],
            'filter.description' => ['sometimes', 'nullable', 'string'],
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }
}
