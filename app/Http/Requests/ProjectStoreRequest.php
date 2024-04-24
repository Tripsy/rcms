<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Queries\ProjectReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProjectStoreRequest extends FormRequest
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
            'authority_name' => ['required', 'string'],
            'authority_key' => ['required', 'string', 'size:32'],
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
                $this->checkProjectExist($validator);
            });
        }
    }

    /**
     * Custom verification logic.
     */
    protected function checkProjectExist(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $project = app(ProjectReadQuery::class)
            ->filterByAuthorityName($validator->safe()->authority_name)
            ->filterByName($validator->safe()->name)
            ->isUnique();

        if ($project === false) {
            $validator->errors()->add(
                'other',
                __('message.project.already_exist')
            );
        }
    }
}
