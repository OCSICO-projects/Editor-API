<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PhoneVerificationRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Http\Services\PhoneNumberVerificationService;
use App\Http\Services\UserService;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    protected $authService;
    protected $phoneNumberVerificationService;
    protected $userService;

    public function __construct(
        AuthService $authService,
        PhoneNumberVerificationService $phoneNumberVerificationService,
        UserService $userService
    ) {
        $this->authService = $authService;
        $this->phoneNumberVerificationService = $phoneNumberVerificationService;
        $this->userService = $userService;
    }

    /**
     * Create user
     *
     * @param RegisterRequest $request
     * @param AuthyApi $authyApi
     * @return \Illuminate\Http\JsonResponse
     * @throws BadRequestHttpException
     */
    public function register(RegisterRequest $request, AuthyApi $authyApi)
    {
        $user = $this->authService->register($request);

        $this->userService->sendEmailVerificationNotification($user);

        $this->phoneNumberVerificationService->sendVerificationToken($user, $authyApi);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return response()->json($this->authService->login($request));
    }

    /**
     * Resend user phone number verification code
     *
     * @param PhoneVerificationRequest $request
     * @param AuthyApi $authyApi
     * @return \Illuminate\Http\JsonResponse
     * @throws BadRequestHttpException
     */
    public function resendPhoneVerificationCode(
        PhoneVerificationRequest $request,
        AuthyApi $authyApi
    ) {
        $user = $request->user;

        $this->phoneNumberVerificationService->sendVerificationToken($user, $authyApi);

        return response()->json([
            'message' => 'Verification code re-sent!'
        ]);
    }

    /**
     * Verify user phone number
     *
     * @param PhoneVerificationRequest $request
     * @param AuthyApi $authyApi
     * @return \Illuminate\Http\JsonResponse
     * @throws BadRequestHttpException
     */
    public function phoneVerification(PhoneVerificationRequest $request, AuthyApi $authyApi)
    {
        $this->phoneNumberVerificationService->finishVerification(
            $request->user,
            $request->token,
            $authyApi
        );

        return response()->json([
            'message' => 'Verified!'
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }
}
