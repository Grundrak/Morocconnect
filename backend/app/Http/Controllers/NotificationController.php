<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $notifications = $user->notifications->map(function($notification) {
            $data = $notification->data;
            if (isset($data['follower_id'])) {
                $follower = User::find($data['follower_id']);
                if ($follower) {
                    $data['follower_name'] = $follower->name;
                    $data['follower_username'] = $follower->username;
                }
            }
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at
            ];
        });

        return response()->json($notifications);
    }
}