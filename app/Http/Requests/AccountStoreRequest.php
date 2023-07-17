<?php

namespace App\Http\Requests;

use App\Enums\AccountStatus;
use App\Models\Account;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AccountStoreRequest extends BaseRequest
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
            'email' => ['email', 'required', Rule::unique(Account::class)],
            'status' => new Enum(AccountStatus::class)
        ];
    }
}
