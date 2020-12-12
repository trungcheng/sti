<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Request;

/**
 * Class AuthenticateRequest
 * @package App\Http\Requests\Exam
 */
class AuthenticateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.*' => __('auth.failed'),
            'password.*' => __('auth.failed'),
        ];
    }
}
