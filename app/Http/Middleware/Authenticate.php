<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $token = $request->bearerToken();

        // Check if a user exists with this token
        $user = User::where('api_token', hash('sha256', $token))->first();


        return response()->json([
            'status' => false,
            'error' => 'Unauthorized',
            'message' => 'You are not logged in. Please log in to continue.',
        ], 401);
         
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if (!$request->expectsJson()) {
            return route('login');
        }

        auth()->setUser($user);

        return $next($request);
    }
}
