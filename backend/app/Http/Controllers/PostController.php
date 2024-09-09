<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\BadgeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
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
            'image' => 'nullable|image|max:1024', // 1MB Max
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
        $post->increment('likes');
        return response()->json(['likes' => $post->likes]);
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
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
