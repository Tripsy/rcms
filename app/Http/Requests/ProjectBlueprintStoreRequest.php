<?php

namespace App\Http\Requests;

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Queries\ProjectBlueprintReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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

    public function messages(): array
    {
        return [
            "components.*.component_type.Illuminate\Validation\Rules\Enum" => 'The component type field can be: '.BlueprintComponentType::listKeys(),
            "components.*.component_format.Illuminate\Validation\Rules\Enum" => 'The component format field can be: '.BlueprintComponentFormat::listKeys(),
            "components.*.is_required.Illuminate\Validation\Rules\Enum" => 'The `is_required` field can be: '.DefaultOption::listKeys(),
            "components.*.status.Illuminate\Validation\Rules\Enum" => 'The status field can be: '.CommonStatus::listKeys(),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', Rule::enum(CommonStatus::class)],
            'components' => ['required', 'array'],
            'components.*.name' => ['required', 'string', 'max:64'],
            'components.*.description' => ['required', 'string', 'max:255'],
            'components.*.info' => ['sometimes', 'nullable', 'string'],
            'components.*.component_type' => ['required', Rule::enum(BlueprintComponentType::class)],
            'components.*.component_format' => ['required', Rule::enum(BlueprintComponentFormat::class)],
            'components.*.type_options' => ['sometimes', 'nullable', 'array'],
            'components.*.is_required' => ['required', Rule::enum(DefaultOption::class)],
            'components.*.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }

    /**
     * Customize the validator instance.
     */
    protected function withValidator(Validator $validator): void
    {
        if ($validator->fails() === false) {
            $validator->after(function ($validator) {
                $has_error = false;

                $components = $this->validator->safe()->components;

                $componentsName = [];

                foreach ($components as $k => $v) {
                    if (in_array($v['name'], $componentsName) === false) {
                        $componentsName[] = $v['name'];

                        if (in_array($v['component_type'], ['select', 'radio', 'checkbox']) && empty($v['type_options'])) {
                            $has_error = true;

                            $validator->errors()->add(
                                'components',
                                __('validation.custom.components.type_options', [
                                    'k' => $k,
                                ])
                            );
                        }
                    } else {
                        $has_error = true;

                        $validator->errors()->add(
                            'components',
                            __('validation.custom.components.names', [
                                'k' => $k,
                                'name' => $v['name'],
                            ])
                        );
                    }
                }

                if ($has_error === false) {
                    $this->checkProjectBlueprintExist($validator);
                }
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkProjectBlueprintExist(\Illuminate\Contracts\Validation\Validator $validator): void
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
