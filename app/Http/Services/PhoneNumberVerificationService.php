<?php

namespace App\Http\Services;

use App\Models\User;
use Authy\AuthyApi;
use Illuminate\Support\Facades\App;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PhoneNumberVerificationService
{
    private $phoneUtil;
    private $isProduction;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
        $this->isProduction = App::environment('production');
    }

    /**
     * @param User $user
     * @param AuthyApi $authyApi
     * @throws BadRequestHttpException
     */
    public function sendVerificationToken(User $user, AuthyApi $authyApi)
    {
        if ($this->isProduction) {
            self::registerUserInAuthy($user, $authyApi);
            self::requestVerifyToken($user, $authyApi);
        }
    }

    /**
     * @param User $user
     * @param String $token
     * @param AuthyApi $authyApi
     * @return bool
     * @throws BadRequestHttpException
     */
    public function finishVerification(User $user, String $token, AuthyApi $authyApi)
    {
        if ($this->isProduction) {
            $verification = $authyApi->verifyToken($user->authy_id, $token);

            if (!$verification->ok()) {
                $errors = $this->getAuthyErrors($verification->errors());
                throw new BadRequestHttpException($errors);
            }
        } else if ($token !== config('twilio.test_token')) {
            throw new BadRequestHttpException('Code you entered is not valid!');
        }

        $user->phone_number_verified_at = now();
        $user->save();

        return true;
    }

    /**
     * @param string $phoneNumber
     * @return \libphonenumber\PhoneNumber
     * @throws BadRequestHttpException
     */
    private function parsePhoneNumber(string $phoneNumber)
    {
        try {
            $result = $this->phoneUtil->parse($phoneNumber);
        } catch (NumberParseException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$this->phoneUtil->isValidNumber($result)) {
            throw new BadRequestHttpException('Number is invalid!');
        }

        return $result;
    }

    /**
     * @param User $user
     * @param AuthyApi $authyApi
     * @throws BadRequestHttpException
     */
    private function registerUserInAuthy(User $user, AuthyApi $authyApi)
    {
        if (!$user->authy_id) {
            $phoneNumber = self::parsePhoneNumber($user->phone_number);
            $authyUser = $authyApi->registerUser(
                $user->email,
                $phoneNumber->getNationalNumber(),
                $phoneNumber->getCountryCode()
            );
            if ($authyUser->ok()) {
                $user->authy_id = $authyUser->id();
                $user->save();
            } else {
                $errors = $this->getAuthyErrors($authyUser->errors());
                throw new BadRequestHttpException($errors);
            }
        }
    }

    /**
     * @param User $user
     * @param AuthyApi $authyApi
     * @throws BadRequestHttpException
     */
    private function requestVerifyToken(User $user, AuthyApi $authyApi)
    {
        $sms = $authyApi->requestSms($user->authy_id);
        if (!$sms->ok()) {
            $errors = $this->getAuthyErrors($sms->errors());
            throw new BadRequestHttpException($errors);
        }
    }

    /**
     * @param $authyErrors
     * @return array
     */
    private function getAuthyErrors($authyErrors)
    {
        $errors = [];
        foreach ($authyErrors as $field => $message) {
            array_push($errors, $field . ': ' . $message);
        }
        return $errors;
    }
}
