<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'username' => 'testuser' . rand(1000, 9999),
            'email' => 'testuser' . rand(1000, 9999) . '@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'user' => ['id', 'name', 'email', 'username'],
                'token',
            ]);
    }
    public function test_login()
    {
        dump('Starting test: ' . __METHOD__);
        dump('Current transaction level: ' . DB::transactionLevel());
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'username', 'roles'],
            ]);
    }

    public function test_logout()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test'.rand(1000,9999).'@example.com',
            'password' => Hash::make('password'),
            'username' => 'testuser'.rand(1000,9999),
        ]);
    
        $token = JWTAuth::fromUser($user);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');
    
        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }
    
}