<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [$user, $token];
    }

    public function test_follow_user()
    {
        [$user1, $token1] = $this->createAuthenticatedUser();
        [$user2, $token2] = $this->createAuthenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->postJson("/api/users/{$user2->id}/follow");

        $response->assertStatus(200)
            ->assertJson(['message' => 'User followed successfully']);

        $this->assertTrue($user1->isFollowing($user2));
    }

    public function test_unfollow_user()
    {
        [$user1, $token1] = $this->createAuthenticatedUser();
        [$user2, $token2] = $this->createAuthenticatedUser();

        $user1->following()->attach($user2);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->postJson("/api/users/{$user2->id}/unfollow");

        $response->assertStatus(200)
            ->assertJson(['message' => 'User unfollowed successfully']);

        $this->assertFalse($user1->isFollowing($user2));
    }

    public function test_get_followers()
    {
        [$user1, $token1] = $this->createAuthenticatedUser();
        [$user2, $token2] = $this->createAuthenticatedUser();

        $user2->following()->attach($user1);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->getJson("/api/users/{$user1->id}/followers");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email']
            ]);
    }

    public function test_get_following()
    {
        [$user1, $token1] = $this->createAuthenticatedUser();
        [$user2, $token2] = $this->createAuthenticatedUser();

        $user1->following()->attach($user2);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->getJson("/api/users/{$user1->id}/following");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email']
            ]);
    }
}