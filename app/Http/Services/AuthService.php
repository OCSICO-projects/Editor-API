<?php

namespace App\Http\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserSocialiteToken;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    /**
     * @param RegisterRequest $request
     * @return User
     */
    public function register(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $request->password
        ]);
    }

    /**
     * @param LoginRequest $request
     * @return array
     */
    public function login(LoginRequest $request)
    {
        return self::generateAccessToken($request->user);
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
    }

    /**
     * @param string $provider
     * @return User
     */
    public function findOrCreateSocialiteUser(string $provider)
    {
        $data = Socialite::driver($provider)->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $data->getEmail()
        ], [
            'name' => $data->getName()
        ]);

        UserSocialiteToken::updateOrCreate([
           'user_id' => $user->id,
           'provider' => $provider
        ], [
            'token' => $data->token,
            'refresh_token' => $data->refreshToken,
            'expires_in' => $data->expiresIn
        ]);

        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    public function generateAccessToken(User $user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return [
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString()
        ];
    }
}
