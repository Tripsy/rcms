<?php

namespace App\Http\Requests;

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\DefaultOption;
use App\Queries\BlueprintComponentReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BlueprintComponentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
        $blueprintComponent = app(BlueprintComponentReadQuery::class)
            ->filterByProjectBlueprintId($this->route('projectBlueprint')->id)
            ->filterByName($this->validator->safe()->name)
            ->filterById($this->route('blueprintComponent')->id, '<>') //ignore updated entry
            ->isUnique();

        if ($blueprintComponent === false) {
            $validator->errors()->add(
                'other',
                __('message.blueprint_component.already_exist')
            );
        }
    }
}
