<?php

namespace App\Http\Requests;

use App\Enums\DefaultOption;
use App\Queries\TagsQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TagsUpdateRequest extends FormRequest
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
            'description' => $this->description ?? null,
            'is_category' => $this->is_category ?? null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_category' => ['sometimes', 'nullable', Rule::enum(DefaultOption::class)],
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
        $tags = app(TagsQuery::class)
            ->filterByProjectId($this->route('project')->id)
            ->filterByName($this->validator->safe()->name)
            ->filterById($this->route('tags')->id, '<>') //ignore updated entry
            ->isUnique();

        if ($tags === false) {
            $validator->errors()->add(
                'other',
                __('message.tags.already_exist')
            );
        }
    }
}
