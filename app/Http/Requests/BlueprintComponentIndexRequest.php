<?php

namespace App\Http\Requests;

use App\Enums\CommonStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlueprintComponentIndexRequest extends FormRequest
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
            'page' => (int) $this->page ?? 1,
            'limit' => (int) $this->limit ?? 5,
            'filter' => [
                'status' => $this->filter['status'] ?? '',
            ],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer'],
            'limit' => ['required', 'integer', 'max:15'],
            'filter.status' => ['sometimes', Rule::enum(CommonStatus::class)],
        ];
    }
}
