<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToFrontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the request is expecting JSON (API request), return JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'redirect_to' => env('FRONTEND_URL', 'http://localhost:3000') . '/login'
            ], 401);
        }

        // For web requests, redirect to frontend login page
        return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/login');
    }
}