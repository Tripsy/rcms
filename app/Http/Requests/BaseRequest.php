<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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
        //TODO handle the else part

        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => __('message.failed'),
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $response = redirect()
                ->route('guest.login')
                ->with('message', __('message.failed'))
                ->withErrors($validator);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
