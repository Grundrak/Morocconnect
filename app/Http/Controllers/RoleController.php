<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();
        $user->roles()->sync([$role->id]);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function getUserRoles(User $user)
    {
        return response()->json(['roles' => $user->roles]);
    }
}