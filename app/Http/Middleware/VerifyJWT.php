<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class VerifyJWT
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 1. Get token from cookie OR Authorization header
            $token =
                $request->cookie('accessToken') ??
                str_replace('Bearer ', '', $request->header('Authorization'));

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized request: No token provided'
                ], 401);
            }

            // 2. Verify token
            $decoded = JWT::decode(
                $token,
                new Key(env('ACCESS_TOKEN_SECRET'), 'HS256')
            );

            // 3. Find user
            $user = User::select('id', 'email', 'full_name', 'role', 'created_at')
                ->find($decoded->_id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found, Invalid token'
                ], 401);
            }

            // 4. Attach user to request (like req.user)
            $request->merge(['user' => $user]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthorized request',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
