<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'No search query provided'], 400);
        }

        $users = User::where('name', 'like', "%{$query}%")
                     ->orWhere('username', 'like', "%{$query}%")
                     ->limit(5)
                     ->get(['id', 'name', 'username', 'avatar']);

        $posts = Post::where('title', 'like', "%{$query}%")
                     ->orWhere('content', 'like', "%{$query}%")
                     ->with('user:id,name,username,avatar')
                     ->limit(5)
                     ->get(['id', 'title', 'content', 'user_id', 'created_at']);

        $comments = Comment::where('content', 'like', "%{$query}%")
                           ->with(['user:id,name,username,avatar', 'post:id,title'])
                           ->limit(5)
                           ->get(['id', 'content', 'user_id', 'post_id', 'created_at']);

        return response()->json([
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }
}