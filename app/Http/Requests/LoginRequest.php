<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    public $user;

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
            'email' => [
                'required',
                'string',
                'email',
                'exists:users'
            ],
            'password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $user = $this->user = User::whereEmail($this->email)->first();

                    if ($user && !Hash::check($this->password, $user->password)) {
                        $fail('Password is incorrect');
                    }

                    if (!$user->hasVerifiedPhone()) {
                        $fail('Phone number is not verified!');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'User with this email doesn\'t exist'
        ];
    }
}
