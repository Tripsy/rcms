<?php

namespace App\Http\Requests;

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Queries\BlueprintComponentQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BlueprintComponentStoreRequest extends FormRequest
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
            'status' => $this->input('status', ''),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'info' => ['sometimes', 'nullable', 'string'],
            'component_type' => ['required', Rule::enum(BlueprintComponentType::class)],
            'component_format' => ['required', Rule::enum(BlueprintComponentFormat::class)],
            'type_options' => ['sometimes', 'nullable', 'array'],
            'is_required' => ['required', Rule::enum(DefaultOption::class)],
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
                $this->checkBlueprintComponentExist($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkBlueprintComponentExist(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $blueprintComponent = app(BlueprintComponentQuery::class)
            ->filterByProjectBlueprintId($this->route('projectBlueprint')->id)
            ->filterByName($this->validator->safe()->name)
            ->isUnique();

        if ($blueprintComponent === false) {
            $validator->errors()->add(
                'other',
                __('message.blueprint_component.already_exist')
            );
        }
    }
}
