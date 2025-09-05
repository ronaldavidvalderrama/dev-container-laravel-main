<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if(!user || !$user->hasRole($roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado o sin salida'
            ], 403);
        }
        return $next($request);
    }
}
