<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'authority_name' => ['string', 'required'],
            'status' => ['sometimes', new Enum(CommonStatus::class)],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(ProjectRepositoryInterface $projectRepository): array
    {
        return [
            function (Validator $validator) use ($projectRepository) {
                if ($projectRepository->isUnique($validator->safe()->authority_name, $validator->safe()->name) === false) {
                    $validator->errors()->add(
                        'other',
                        __('message.project.already_exist')
                    );
                }
            }
        ];
    }
}
