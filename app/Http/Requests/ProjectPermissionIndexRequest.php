<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectPermissionIndexRequest extends FormRequest
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
                'user_name' => '',
                'role' => '',
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
            'filter.user_name' => ['sometimes', 'nullable', 'string'],
            'filter.role' => ['sometimes', Rule::enum(ProjectPermissionRole::class)],
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }
}
