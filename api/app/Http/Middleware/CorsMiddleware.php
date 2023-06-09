<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, x-api-key',
        ];

        if ($request->getMethod() === 'OPTIONS') {
            // The request is a preflight request, respond with 200 OK and the necessary headers
            return response('', 200)->withHeaders($headers);
        }

        // Add the CORS headers to the incoming request
        return $next($request)->withHeaders($headers);
    }
}

