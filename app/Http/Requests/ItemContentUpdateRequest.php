<?php

namespace App\Http\Requests;

use App\Queries\ItemContentQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ItemContentUpdateRequest extends FormRequest
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
            ->filterById($this->route('itemContent')->id, '<>') //ignore updated entry
            ->isUnique();

        if ($itemContent === false) {
            $validator->errors()->add(
                'other',
                __('message.itemContent.already_exist')
            );
        }
    }
}
