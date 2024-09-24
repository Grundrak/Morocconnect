<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        /** @var User $follower */
        $follower = Auth::user();
        
        if ($follower->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }

        if (!$follower->isFollowing($user)) {
            $follower->following()->attach($user->id);
            $user->notify(new NewFollowerNotification($follower->id)); //this line will send the id of follower to NewFollower notification
            return response()->json(['message' => 'User followed successfully']);
        }

        return response()->json(['message' => 'You are already following this user'], 400);
    }
    public function unfollow(User $user)
    {
        /** @var User $follower */
        $follower = Auth::user();

        if ($follower->isFollowing($user)) {
            $follower->following()->detach($user->id);
            return response()->json(['message' => 'User unfollowed successfully']);
        }

        return response()->json(['message' => 'You are not following this user'], 400);
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->get();
        return response()->json($followers);
    }
    public function following(User $user)
    {
        $following = $user->following()->get();
        return response()->json($following);
    }
}
