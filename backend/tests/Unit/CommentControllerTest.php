<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [$user, $token];
    }

    public function test_store_comment()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/post/{$post->id}/comments", [
            'content' => 'This is a test comment'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'content', 'user_id', 'post_id']);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    public function test_update_comment()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/comments/{$comment->id}", [
            'content' => 'Updated comment'
        ]);

        $response->assertStatus(200)
            ->assertJson(['content' => 'Updated comment']);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment'
        ]);
    }

    public function test_delete_comment()
    {
        [$user, $token] = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}