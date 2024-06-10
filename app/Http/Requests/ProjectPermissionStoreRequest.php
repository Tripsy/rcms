<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\ProjectPermissionRole;
use App\Queries\ProjectPermissionQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => (int) $this->user_id,
            'status' => $this->status ?? '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int', 'exists:App\Models\User,id'],
            'role' => ['sometimes', Rule::enum(ProjectPermissionRole::class)],
            'status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }

    /**
     * Customize the validator instance.
     */
    protected function withValidator(Validator $validator): void
    {
        if ($validator->fails() === false) {
            $validator->after(function ($validator) {
                $this->checkProjectPermissionExist($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkProjectPermissionExist(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $projectPermission = app(ProjectPermissionQuery::class)
            ->filterByProjectId($this->route('project')->id)
            ->filterByUserId($this->validator->safe()->user_id)
            ->isUnique();

        if ($projectPermission === false) {
            $validator->errors()->add(
                'other',
                __('message.project_permission.already_exist')
            );
        }
    }
}
