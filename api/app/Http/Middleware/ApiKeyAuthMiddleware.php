<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiKeyAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return response()->json(['error' => 'API key is missing.'], 401);
        }
        $apiKeyHash = DB::table('api_keys')->pluck('key');

        foreach($apiKeyHash as $key){
            if (!password_verify($apiKey, $key)) {
                return response()->json(['Error'=> 'Invalid API key.'], 401);
            }
        }
        $contentType = $request->header('Content-Type');
        if (isset($contentType) && $contentType !== 'application/json') {
            return response()->json(['error' => 'Unsupported content type.'], 400);
        }

        return $next($request);
    }
}

