<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'page' => (int) $this->page ?? 1,
            'limit' => (int) $this->limit ?? 5,
            'filter' => [
                'user_name' => $this->filter['user_name'] ?? '',
                'role' => $this->filter['role'] ?? '',
                'status' => $this->filter['status'] ?? '',
            ]
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
            'filter.user_name' => ['sometimes', 'nullable', 'string'],
            'filter.role' => ['sometimes', new Enum(ProjectPermissionRole::class)],
            'filter.status' => ['sometimes', new Enum(CommonStatus::class)],
        ];
    }
}
