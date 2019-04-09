<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
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
            'signature' => [
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $this->user()['email'])->first();
                    if ($user === null) {
                        $fail('User not found!');
                    }
                    if ($user->hasVerifiedEmail()) {
                        $fail('Email already verified!');
                    }
                }
            ]
        ];
    }
}
