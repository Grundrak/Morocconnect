<?php



namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboardData()
    {
        return response()->json([
            'totalUsers' => User::count(),
            'totalPosts' => Post::count(),
            'totalComments' => Comment::count(),
            'activeUsers' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            'userGrowthData' => $this->getUserGrowthData(),
            'postActivityData' => $this->getPostActivityData(),
        ]);
    }

    private function getUserGrowthData()
    {
        return User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPostActivityData()
    {
        return Post::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
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
