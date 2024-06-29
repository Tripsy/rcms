<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Models\BlueprintComponent;
use App\Queries\ItemContentQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ItemContentStoreRequest extends FormRequest
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
            'blueprint_component_id' => ['required', 'integer'],
            'content' => ['required', 'string'],
        ];
    }

    /**
     * Customize the validator instance.
     */
    protected function withValidator(Validator $validator): void
    {
        if ($validator->fails() === false) {
            $validator->after(function ($validator) {
                $this->checkBlueprintComponent($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkBlueprintComponent(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $exists = BlueprintComponent::where('id', $validator->safe()->blueprint_component_id)
            ->whereHas('blueprint', function ($query) {
                $query->whereHas('items', function ($query) {
                    $query->where('id', $this->route('item')->id);
                });
            })
            ->exists();

        if ($exists === false) {
            $validator->errors()->add(
                'other',
                __('validation.custom.components.invalid')
            );
        }
    }
}
