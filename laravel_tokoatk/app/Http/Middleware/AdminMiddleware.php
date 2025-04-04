<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Redirect to home page if not an admin
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}
