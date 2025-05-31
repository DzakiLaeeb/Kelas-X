<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponseMiddleware
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
        // Force Accept header to application/json
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // Force Content-Type header to application/json
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
