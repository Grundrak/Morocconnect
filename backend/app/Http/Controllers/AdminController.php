<?php



namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboardData()
    {
        return response()->json([
            'userCount' => User::count(),
            'postCount' => Post::count(),
            'commentCount' => Comment::count(),
        ]);
    }

    public function users()
    {
        return User::with('roles')->get();
    }

    public function posts()
    {
        return Post::with('user')->get();
    }

    public function comments()
    {
        return Comment::with(['user', 'post'])->get();
    }
}