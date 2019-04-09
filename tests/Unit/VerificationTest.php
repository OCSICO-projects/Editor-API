<?php

namespace Tests\Unit;

use Tests\TestCase;

class VerificationTest extends TestCase
{
    public function testResendPhoneVerificationCode()
    {
        $this->createTestUser(false);

        $response = $this->json('POST', '/auth/phone/resend-code', [
            'phone_number' => self::USER_PHONE_NUMBER
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'message' => 'Verification code re-sent!'
        ]);
    }

    public function testPhoneVerification()
    {
        $this->createTestUser(false);

        $response = $this->json('POST', '/auth/phone/verify', [
            'phone_number' => self::USER_PHONE_NUMBER,
            'token' => $this->verifyToken
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'message' => 'Verified!'
        ]);
    }
}
