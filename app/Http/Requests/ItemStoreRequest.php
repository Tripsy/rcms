<?php

namespace App\Http\Requests;

use App\Enums\ProjectItemStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int', Rule::exists(Project::class, 'id')],
            'status' => ['sometimes', Rule::enum(ProjectItemStatus::class)],
            'description' => ['present', 'string'],
            'data.*.label' => ['required', 'string', 'max:64'],
            'data.*.content' => ['present', 'string'],
        ];
    }
}
