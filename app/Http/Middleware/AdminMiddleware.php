<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For now, we'll allow any authenticated user to access admin
        // In a real application, you'd check for admin role/permissions
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // TODO: Add proper admin role checking
        // if (!Auth::user()->isAdmin()) {
        //     abort(403, 'Unauthorized access.');
        // }

        return $next($request);
    }
} 