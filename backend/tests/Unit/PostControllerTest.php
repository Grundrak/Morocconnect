<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test' . rand(1000, 9999) . '@example.com',
            'password' => 'password',
            'username' => 'testuser' . rand(1000, 9999),
        ]);
        $token = JWTAuth::fromUser($user);
        return [$user, $token];
    }


    public function test_store()
    {
        [$user, $token] = $this->createAuthenticatedUser();
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/post', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ]);
    
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'title', 'content', 'user_id', 'created_at', 'updated_at'
            ]);
    
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ]);
    }
    public function test_index()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        Post::factory()->count(5)->create();
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/post');
    
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'user_id', 'created_at', 'updated_at']
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);
    }
    
    public function test_show()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/post/{$post->id}");
    
        $response->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
            ]);
    }

    public function test_update()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create(['user_id' => $user->id]);  
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/post/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    
        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'content' => 'Updated content',
            ]);
    }
    
    public function test_destroy()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create(['user_id' => $user->id]);  // Create post for this user
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/post/{$post->id}");
    
        $response->assertStatus(204);
    
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
