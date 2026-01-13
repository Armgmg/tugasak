<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class ValidateApiToken
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('API Request:', [
            'path' => $request->path(),
            'auth_header' => $request->header('Authorization'),
        ]);

        // Check for bearer token
        $token = $request->bearerToken();
        
        if ($token) {
            // Find the token in database
            $accessToken = PersonalAccessToken::findToken($token);
            
            Log::info('Token found:', [
                'found' => $accessToken !== null,
                'token' => $token ? substr($token, 0, 20) . '...' : null,
            ]);

            if ($accessToken) {
                // Set the user on the request
                $request->setUserResolver(function () use ($accessToken) {
                    return $accessToken->tokenable;
                });

                Log::info('User set:', [
                    'user_id' => $accessToken->tokenable->id ?? null,
                ]);
            }
        }

        return $next($request);
    }
}
