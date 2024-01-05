<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Queries\ProjectPermissionReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProjectPermissionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'User #:input not found',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => (int) $this->user_id,
            'status' => $this->status ?? ''
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int', 'exists:App\Models\User,id'],
            'role' => ['sometimes', new Enum(ProjectPermissionRole::class)],
            'status' => ['sometimes', new Enum(CommonStatus::class)],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(ProjectPermissionReadQuery $query): array
    {
        return [
            function () use ($query) {
                $project = $query
                    ->filterByProjectId($this->route('project')->id)
                    ->filterByUserId($this->validator->safe()->user_id)
                    ->isUnique();

                if ($project === false) {
                    $this->validator->errors()->add(
                        'other',
                        __('message.project_permission.already_exist')
                    );
                }
            }
        ];
    }
}
