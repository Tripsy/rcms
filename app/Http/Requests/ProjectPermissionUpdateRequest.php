<?php

namespace App\Http\Requests;

use App\Enums\ProjectPermissionRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProjectPermissionUpdateRequest extends FormRequest
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
            'role' => ['sometimes', new Enum(ProjectPermissionRole::class)],
        ];
    }
}
