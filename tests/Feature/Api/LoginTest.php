<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_existing_user(): void
    {
        $user = User::create([
            'name' => 'Ali',
            'email' => 'ali@domain.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => "Ali's Android"
        ]);

        $response->assertSuccessful();

        $this->assertNotEmpty($response->getContent());

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => "Ali's Android",
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_get_user_from_token(): void
    {
        $user = User::create([
            'name' => 'Ali',
            'email' => 'ali@domain.com',
            'password' => bcrypt('password')
        ]);

        $token = $user->createToken("Ali's Android")->plainTextToken;

        $response = $this->get('/api/user', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertSuccessful();

        $response->assertJson(function ($json) {
            $json->where('email', 'ali@domain.com')
                ->missing('password')
                ->etc();
        });
    }
}
