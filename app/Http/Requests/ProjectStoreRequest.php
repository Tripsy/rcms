<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Queries\ProjectReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
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
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status ?? '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'authority_name' => ['required', 'string'],
            'authority_key' => ['required', 'string', 'size:32'],
            'status' => ['sometimes', new Enum(CommonStatus::class)],
        ];
    }

    /**
     * Customize the validator instance.
     *
     * @param Validator $validator
     * @return void
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
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
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
