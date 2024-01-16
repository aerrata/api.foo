<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function register_new_user(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Ali',
            'email' => 'ali@domain.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'device_name' => 'Android'
        ]);

        $response->assertSuccessful();

        $this->assertNotEmpty($response->getContent());

        $this->assertDatabaseHas('users', ['email' => 'ali@domain.com']);

        $this->assertDatabaseHas('personal_access_tokens', ['name' => 'Android']);
    }
}
