<?php

namespace App\Http\Requests;

use App\Enums\ItemStatus;
use App\Models\Account;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ItemStoreRequest extends BaseRequest
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
            'account_id' => ['int', 'required', Rule::exists(Account::class, 'id')],
            'status' => ['sometimes', new Enum(ItemStatus::class)],
            'description' => ['present', 'string'],
            'data.*.label' => ['required', 'string', 'max:64'],
            'data.*.content' => ['present', 'string'],
        ];
    }
}
