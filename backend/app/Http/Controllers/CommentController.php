<?php

namespace App\Http\Controllers;

use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->withCount('replies')->get();
    
        return response()->json($comments);
    }


    public function store(Request $request, Post $post)
    {
        Log::info('Entering store method', [
            'user' => Auth::id(),
            'post' => $post->id,
            'request_data' => $request->all()
        ]);

        if (!$post->exists) {
            Log::warning('Post not found', ['post_id' => $post->id]);
            return response()->json(['error' => 'Post not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'content' => 'required|string',
                'parent_id' => 'nullable|exists:comments,id'
            ]);

            Log::info('Data validated', ['validatedData' => $validatedData]);

            $comment = new Comment();
            $comment->content = $validatedData['content'];
            $comment->user_id = Auth::id();
            $comment->post_id = $post->id;
            $comment->parent_id = $validatedData['parent_id'] ?? null;
            $comment->save();

            Log::info('Comment saved', ['comment' => $comment->toArray()]);
            $post->user->notify(new NewCommentNotification($comment)); //this line send $comment to the New comment notification 
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to create comment: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validatedData = $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($validatedData);

        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}
