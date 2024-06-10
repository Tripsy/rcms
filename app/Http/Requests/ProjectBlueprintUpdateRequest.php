<?php

namespace App\Http\Requests;

use App\Enums\BlueprintComponentFormat;
use App\Enums\BlueprintComponentType;
use App\Enums\DefaultOption;
use App\Queries\ProjectBlueprintQuery;
use App\Repositories\BlueprintComponentRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProjectBlueprintUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            "components.*.component_type.Illuminate\Validation\Rules\Enum" => 'The component type field can be: '.BlueprintComponentType::listKeys(),
            "components.*.component_format.Illuminate\Validation\Rules\Enum" => 'The component format field can be: '.BlueprintComponentFormat::listKeys(),
            "components.*.is_required.Illuminate\Validation\Rules\Enum" => 'The `is_required` field can be: '.DefaultOption::listKeys(),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'info' => ['sometimes', 'nullable', 'string'],
            'components' => ['sometimes', 'array'],
            'components.*.name' => ['required', 'string', 'max:64'],
            'components.*.description' => ['required', 'string', 'max:255'],
            'components.*.info' => ['sometimes', 'nullable', 'string'],
            'components.*.component_type' => ['required', Rule::enum(BlueprintComponentType::class)],
            'components.*.component_format' => ['required', Rule::enum(BlueprintComponentFormat::class)],
            'components.*.type_options' => ['sometimes', 'nullable', 'array'],
            'components.*.is_required' => ['required', Rule::enum(DefaultOption::class)],
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

                $components = $this->validator->safe()->components ?? [];

                if (empty($components) === false) {
                    $componentsName = [];

                    $blueprintComponentRepository = app(BlueprintComponentRepository::class);

                    foreach ($components as $k => $v) {
                        if (in_array($v['name'], $componentsName) === false) {
                            $componentsName[] = $v['name'];

                            $validationErrors = $blueprintComponentRepository->validateComponentStructure($v);

                            if (empty($validationErrors) === false) {
                                $has_error = true;

                                foreach ($validationErrors as $validationErrorMessage) {
                                    $validator->errors()->add(
                                        'components',
                                        __($validationErrorMessage, [
                                            'k' => $k,
                                        ])
                                    );
                                }
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
        $projectBlueprint = app(ProjectBlueprintQuery::class)
            ->filterByProjectId($this->route('project')->id)
            ->filterByName($this->validator->safe()->name)
            ->filterById($this->route('projectBlueprint')->id, '<>') //ignore updated entry
            ->isUnique();

        if ($projectBlueprint === false) {
            $validator->errors()->add(
                'other',
                __('message.project_blueprint.already_exist')
            );
        }
    }
}
