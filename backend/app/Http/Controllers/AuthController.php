<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name ?? '',
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['general' => 'An error occurred during registration.']
            ], 500);
        }
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|string'
        ]);

        $user = User::find(Auth::id());
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->avatar = $request->avatar;
        $user->save();

        return response()->json($user);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Manually fetch roles using the Role model
        $roles = \App\Models\Role::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->pluck('name')->toArray();

        // Create a custom user array with roles
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            // Add any other user fields you need
            'roles' => $roles
        ];

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $userData
        ]);
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
    public function suggestedUsers(Request $request)
    {
        $currentUser = $request->user();
        $suggestedUsers = User::where('id', '!=', $currentUser->id)
            ->whereDoesntHave('followers', function ($query) use ($currentUser) {
                $query->where('follower_id', $currentUser->id);
            })
            ->inRandomOrder()
            ->take(5)
            ->get(['id', 'name', 'username', 'avatar']);

        return response()->json($suggestedUsers);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
        ]);

        foreach ($validatedData as $key => $value) {
            if (!is_null($value)) {
                $user->$key = $value;
            }
        }

        $user->save();

        return response()->json($user);
    }
}
