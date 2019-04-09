<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PhoneVerificationRequest extends FormRequest
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
            'phone_number' => [
                'required',
                'string',
                'exists:users',
                function ($attribute, $value, $fail) {
                    $user = $this->user = User::where('phone_number', $this->phone_number)->first();
                    if ($user->hasVerifiedPhone()) {
                        $fail('Phone number already veified!');
                    }
                }
            ],
            'token' => [
                'string'
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone_number.exists' => 'User with this phone number doesn\'t exist'
        ];
    }
}
