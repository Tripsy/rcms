<?php

namespace App\Http\Requests;

use App\Queries\BlueprintComponentReadQuery;
use Illuminate\Foundation\Http\FormRequest;
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
            ->filterById($this->route('blueprintComponent')->id, '<>') //ignore updated entry
            ->isUnique();

        if ($blueprintComponent === false) {
            $validator->errors()->add(
                'other',
                __('message.blueprintComponent.already_exist')
            );
        }
    }
}
