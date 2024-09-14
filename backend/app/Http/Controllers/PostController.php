<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\BadgeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PostLikedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:1024',
            'verification_request' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $post = User::find(Auth::id())->posts()->create($validatedData);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post_images', 'public');
            $post->image = $path;
            $post->save();
        }

        if ($request->has('verification_request')) {
            $post->requestVerification($request->verification_request);
        }

        $this->badgeService->checkAndAwardBadges($user);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:1000',
            'image' => 'nullable|image|max:1024',
        ]);

        $post->update($validatedData);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post_images', 'public');
            $post->image = $path;
            $post->save();
        }

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }

    public function verify(Post $post)
    {
        $this->authorize('verify', $post);
        $post->verify();
        return response()->json($post);
    }

    public function share(Post $post)
    {
        $post->increment('shares');
        return response()->json(['shares' => $post->shares]);
    }

    public function like(Post $post)
    {
        $user = Auth::user();
        // Check if the user has already liked the post
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You have already liked this post'], 400);
        }
        // Create a new like
        $post->likes()->create(['user_id' => $user->id]);
        // Increment the likes count
        $post->increment('likes');
        // Notify the post owner if it's not the same as the liker
        if ($post->user_id !== $user->id) {
            $post->user->notify(new PostLikedNotification($post, User::find($user->id)));
        }
        return response()->json([
            'message' => 'Post liked successfully',
            'likes' => $post->likes
        ]);
    }
    public function unlike(Post $post)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $like = $post->likes()->where('user_id', $user->id)->first();

        if (!$like) {
            return response()->json(['message' => 'You have not liked this post'], 400);
        }
        $like->delete();
        $post->decrement('likes');
        return response()->json([
            'message' => 'Post unliked successfully',
            'likes' => $post->likes
        ]);
    }

    public function requestVerification(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'verification_request' => 'required|string|max:1000',
        ]);

        $post->requestVerification($validatedData['verification_request']);

        return response()->json($post);
    }

    public function rejectVerification(Post $post)
    {
        $this->authorize('verify', $post);

        $post->rejectVerification();

        return response()->json($post);
    }
}
