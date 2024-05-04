<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Queries\BlueprintComponentReadQuery;
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
            'status' => $this->status ?? '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
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
        $blueprintComponent = app(BlueprintComponentReadQuery::class)
            ->filterByName($validator->safe()->name)
            ->isUnique();

        if ($blueprintComponent === false) {
            $validator->errors()->add(
                'other',
                __('message.blueprintComponent.already_exist')
            );
        }
    }
}
