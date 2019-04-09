<?php

namespace App\Http\Controllers;

use App\Enums\SocialiteProviders;
use App\Http\Services\AuthService;
use App\Http\Services\UserService;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    protected $authService;
    protected $userService;

    public function __construct(
        AuthService $authService,
        UserService $userService
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return mixed
     */
    public function redirectToFacebook()
    {
        return Socialite::driver(SocialiteProviders::FACEBOOK)->stateless()->redirect();
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return mixed
     */
    public function redirectToGoogle()
    {
        return Socialite::driver(SocialiteProviders::GOOGLE)->stateless()->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleFacebookCallback()
    {
        return response()->json(
            self::processSocialiteCallback(SocialiteProviders::FACEBOOK)
        );
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback()
    {
        return response()->json(
            self::processSocialiteCallback(SocialiteProviders::GOOGLE)
        );
    }

    /**
     * @param string $provider
     * @return array
     */
    private function processSocialiteCallback(string $provider)
    {
        $user = $this->authService->findOrCreateSocialiteUser($provider);

        if ($user->wasRecentlyCreated) {
            $this->userService->sendEmailVerificationNotification($user);
        }

        return $this->authService->generateAccessToken($user);
    }
}
