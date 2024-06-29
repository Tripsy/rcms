<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Enums\DefaultOption;
use App\Queries\TagQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TagsStoreRequest extends FormRequest
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
            'description' => $this->description ?? '',
            'is_category' => $this->is_category ?? '',
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
            'description' => ['sometimes', 'string'],
            'is_category' => ['sometimes', 'nullable', Rule::enum(DefaultOption::class)],
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
                $this->checkTagsExist($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkTagsExist(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $tags = app(TagQuery::class)
            ->filterByProjectId($this->route('project')->id)
            ->filterByName($this->validator->safe()->name)
            ->isUnique();

        if ($tags === false) {
            $validator->errors()->add(
                'other',
                __('message.tags.already_exist')
            );
        }
    }
}
