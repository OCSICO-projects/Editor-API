<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users'
            ],
            'phone_number' => [
                'required',
                'string',
                'unique:users'
            ],
            'password' => [
                'required',
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'User with this email already exists',
            'phone.unique' => 'User with this phone number already exists'
        ];
    }
}
