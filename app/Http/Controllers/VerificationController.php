<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneVerificationRequest;
use App\Http\Services\PhoneNumberVerificationService;
use Authy\AuthyApi;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerificationController extends Controller
{
    protected $phoneNumberVerificationService;

    public function __construct(
        PhoneNumberVerificationService $phoneNumberVerificationService
    ) {
        $this->phoneNumberVerificationService = $phoneNumberVerificationService;
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
}
