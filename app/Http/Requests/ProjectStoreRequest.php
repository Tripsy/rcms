<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Queries\ProjectReadQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     * Get the "after" validation callables for the request.
     */
    public function after(ProjectReadQuery $query): array
    {
        return [
            function () use ($query) {
                $project = $query
                    ->filterByAuthorityName($this->validator->safe()->authority_name)
                    ->filterByName($this->validator->safe()->name)
                    ->isUnique();

                if ($project === false) {
                    $this->validator->errors()->add(
                        'other',
                        __('message.project.already_exist')
                    );
                }
            }
        ];
    }
}
