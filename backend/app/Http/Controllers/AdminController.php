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
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'birthday' => 'nullable|date',
          
        ]);
    
        $user->update($validatedData);
    
        return response()->json($user);
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}