<?php

namespace App\Http\Middleware;

use Str;
use Auth;
use Closure;
use Illuminate\Http\Request;
use App\Models\ApiAuthentication;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        if (Str::startsWith($authorizationHeader, 'Basic ')) {
            $base64Credentials = Str::substr($authorizationHeader, 6);
            $credentials = base64_decode($base64Credentials);
            
            list($username, $password) = explode(':', $credentials, 2);

            if (ApiAuthentication::where('username', $username)->where('password', $password)->exists()) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
