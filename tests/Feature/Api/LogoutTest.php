<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function logout_user(): void
    {
        $user = User::create([
            'name' => 'Ali',
            'email' => 'ali@domain.com',
            'password' => bcrypt('password'),
        ]);

        $token = $user->createToken('Android')->plainTextToken;

        $response = $this->post('/api/logout', headers: [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
