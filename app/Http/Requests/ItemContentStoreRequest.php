<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
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
                $this->checkItemContentExist($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkItemContentExist(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $itemContent = app(ItemContentQuery::class)
            ->filterByName($validator->safe()->name)
            ->isUnique();

        if ($itemContent === false) {
            $validator->errors()->add(
                'other',
                __('message.itemContent.already_exist')
            );
        }
    }
}
