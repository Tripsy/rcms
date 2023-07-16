<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    /**
     * Handle the error output
     *
     * @param $validator
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation($validator): void
    {
        //TODO

        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $validator->errors()
            ]);
        } else {
            $response = redirect()
                ->route('guest.login')
                ->with('message', 'Ops! Some errors occurred')
                ->withErrors($validator);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
