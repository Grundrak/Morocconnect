<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'slug' => 'required|string|unique:roles,slug',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $role->id,
            'slug' => 'sometimes|string|unique:roles,slug,' . $role->id,
        ]);

        $role->update($request->all());
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
    public function assignRole(Request $request, User $user)
    {
        Log::info('Assigning role', [
            'user_id' => $user->id,
            'role' => $request->role,
        ]);
    
        $request->validate([
            'role' => 'required|exists:roles,slug',
        ]);
    
        $role = Role::where('slug', $request->role)->firstOrFail();
        $user->roles()->syncWithoutDetaching([$role->id]);
    
        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function getUserRoles(User $user)
    {
        return response()->json(['roles' => $user->roles]);
    }
}