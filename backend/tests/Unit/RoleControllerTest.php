<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log; 

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedAdmin()
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
    
        Log::info('Admin role:', $adminRole->toArray());
    
        $admin->roles()->sync([$adminRole->id]);
    
        Log::info('Admin user roles:', $admin->roles()->select('roles.name', 'roles.id')->pluck('roles.name', 'roles.id')->toArray());
    
        $token = JWTAuth::fromUser($admin);
    
        $admin = $admin->fresh();
    
        Log::info('Admin user roles after refresh:', $admin->roles()->select('roles.name', 'roles.id')->pluck('roles.name', 'roles.id')->toArray());
    
        return [$admin, $token];
    }

    public function test_assign_role()
    {
        [$admin, $token] = $this->createAuthenticatedAdmin();
    
        Log::info('Admin user:', $admin->toArray());
        Log::info('Admin roles:', $admin->roles->toArray());
    
        // Debug: Check if admin has the correct role
        $hasRole = $admin->hasRole('admin');
        Log::info('Admin has admin role: ' . ($hasRole ? 'true' : 'false'));
        $this->assertTrue($hasRole, 'Admin does not have admin role');
    
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Editor', 'slug' => 'editor']);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/users/{$user->id}/assign-role", [
            'role' => 'editor'
        ]);
    
        // Debug: Output response content
        Log::info('Response status: ' . $response->status());
        Log::info('Response content: ' . $response->content());
    
        $response->assertStatus(200)
            ->assertJson(['message' => 'Role assigned successfully']);
    
        $this->assertTrue($user->fresh()->hasRole('editor'));
    }
}