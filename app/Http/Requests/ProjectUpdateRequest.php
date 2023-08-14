<?php

namespace App\Http\Requests;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProjectUpdateRequest extends FormRequest
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
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(ProjectRepositoryInterface $projectRepository): array
    {
        return [
            function (Validator $validator) use ($projectRepository) {
                if ($projectRepository->isUnique($validator->safe()->authority_name, $validator->safe()->name, $this->route('project')->id) === false) {
                    $validator->errors()->add(
                        'other',
                        __('message.project.already_exist')
                    );
                }
            }
        ];
    }
}
