<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Check if the authenticated user's type matches the required user type
        if (auth()->user()->role === $userType) {
            return $next($request);
        }
    
        // Return appropriate error response
        if ($request->expectsJson()) {
            return response()->json(['error' => 'You do not have permission to access this page.'], 403);
        } else {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }
    }
    
}
