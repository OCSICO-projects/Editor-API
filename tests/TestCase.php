<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    const USER_NAME = 'test user';
    const USER_EMAIL = 'test@test.com';
    const USER_PHONE_NUMBER = '+375445555555';
    const USER_PASSWORD = 'secure_test_password';

    protected $verifyToken;

    public function setUp()
    {
        parent::setUp();
        $this->verifyToken = config('twilio.test_token');
    }

    protected function createTestUser($verified = true)
    {
        $verifiedAtValue = $verified ? now() : null;

        return factory(User::class)
            ->create([
                'name' => self::USER_NAME,
                'email' => self::USER_EMAIL,
                'email_verified_at' => $verifiedAtValue,
                'phone_number' => self::USER_PHONE_NUMBER,
                'phone_number_verified_at' => $verifiedAtValue,
                'password' => self::USER_PASSWORD
            ]);
    }
}
