<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Queries\ProjectBlueprintReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class ProjectBlueprintStoreRequest extends FormRequest
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
            'status' => $this->status ?? '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'size:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', new Enum(CommonStatus::class)],
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
        $projectBlueprint = app(ProjectBlueprintReadQuery::class)
            ->filterByProjectId($this->route('project')->id)
            ->filterByDescription($this->validator->safe()->description)
            ->isUnique();

        if ($projectBlueprint === false) {
            $validator->errors()->add(
                'other',
                __('message.project_blueprint.already_exist')
            );
        }
    }
}
