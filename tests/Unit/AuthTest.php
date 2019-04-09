<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testRegisterUser()
    {
        $response = $this->registerTestUser();

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    public function testLoginUser()
    {
        $this->createTestUser();
        $response = $this->loginTestUser();

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'token_type',
            'expires_at'
        ]);
    }

    public function testLogoutUser()
    {
        $this->createTestUser();
        $response = $this->loginTestUser();
        $token = $response->json('token');
        $response = $this->logoutTestUser($token);

        $response->assertStatus(200);
        $response->assertExactJson(['message' => 'Successfully logged out']);
    }

    public function testGetUser()
    {
        $user = $this->createTestUser();

        $response = $this->actingAs($user, 'api')->json('GET', '/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    private function registerTestUser()
    {
        return $this->json('POST', '/auth/register', [
            'name' => self::USER_NAME,
            'email' => self::USER_EMAIL,
            'phone_number' => self::USER_PHONE_NUMBER,
            'password' => self::USER_PASSWORD
        ]);
    }

    private function loginTestUser()
    {
        return $this->json('POST', '/auth/login', [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD
        ]);
    }

    private function logoutTestUser($token)
    {
        return $this->json('GET', '/auth/logout', [], [
            'Authorization' => "Bearer $token"
        ]);
    }
}
