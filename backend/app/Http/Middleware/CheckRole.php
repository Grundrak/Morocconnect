<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            Log::info('User failed role check', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'required_role' => $role,
                'user_roles' => $request->user() ? $request->user()->roles->pluck('slug')->toArray() : []
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
