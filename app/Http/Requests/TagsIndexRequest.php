<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagsIndexRequest extends FormRequest
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
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'filter' => array_merge([
                'name' => '',
                'is_category' => '',
                'status' => '',
            ], $this->input('filter', [])),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'filter.name' => ['sometimes', 'nullable', 'string'],
            'filter.is_category' => ['sometimes', Rule::enum(DefaultOption::class)],
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }
}
