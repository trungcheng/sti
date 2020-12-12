<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Validation\Rule;
use App\Http\Requests\Request;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Api\User
 */
class RegisterRequest extends Request
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                Rule::unique('users')
                    ->where('company_id', $this->company_id)
                    ->whereNull('deleted_at')
            ],
            'email_confirmation' => 'required|same:email',
            'password' => 'required|min:12|max:24|regex:/^[a-zA-Z0-9]+$/',
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'email_confirmation.required' => __('messages.email_confirmation_required'),
            'email_confirmation.same' => __('messages.email_confirmation_and_email_not_match'),
            'password_confirmation.required' => __('messages.password_confirmation_required'),
            'password_confirmation.same' => __('messages.password_confirmation_and_password_not_match'),
            'password.required' => __('messages.validate_password_required'),
            'password.min' => __('messages.validate_password'),
            'password.max' => __('messages.validate_password'),
            'password.regex' => __('messages.validate_password'),
        ];
    }
}
