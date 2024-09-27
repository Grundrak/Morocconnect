<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Badge;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [$user, $token];
    }
    protected function createAuthenticatedAdmin()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'slug' => 'admin']);
        $admin->roles()->sync([$adminRole->id]);
        $token = JWTAuth::fromUser($admin);
        
        // Refresh the admin model to ensure the role is attached
        $admin = $admin->fresh();
        
        return [$admin, $token];
    }
    public function test_award_badge()
{
    [$admin, $adminToken] = $this->createAuthenticatedAdmin();
    [$user, $userToken] = $this->createAuthenticatedUser();
    $badge = Badge::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $adminToken,
    ])->postJson("/api/users/{$user->id}/award-badge", [
        'badge_id' => $badge->id
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Badge awarded successfully']);

    $this->assertTrue($user->fresh()->badges->contains($badge));
}

}