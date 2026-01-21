<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /**
     * Generate Access & Refresh Token
     */
    private function generateAccessAndRefreshToken(User $user): array
    {
        $accessToken = JWT::encode(
            [
                '_id'   => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'exp'  => time() + (int) env('ACCESS_TOKEN_EXPIRY', 3600),
            ],
            env('ACCESS_TOKEN_SECRET'),
            'HS256'
        );

        $refreshToken = JWT::encode(
            [
                '_id' => $user->id,
                'exp' => time() + (int) env('REFRESH_TOKEN_EXPIRY', 604800),
            ],
            env('REFRESH_TOKEN_SECRET'),
            'HS256'
        );

        $user->update([
            'refresh_token' => $refreshToken
        ]);

        return [
            'accessToken'  => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }

    /**
     * REGISTER
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email',
            'fullName' => 'required|string',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::create([
            'email'    => $request->email,
            'name'     => $request->fullName,
            'password' => $request->password,
        ]);

        return response()->json([
            'status'  => 201,
            'data'    => $user->makeHidden(['password', 'refresh_token']),
            'message' => 'User registered successfully',
        ], 201);
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        if (!$request->email) {
            return response()->json([
                'message' => 'Email is required',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found. Please register first.',
            ], 404);
        }

        if (!$user->comparePassword($request->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tokens = $this->generateAccessAndRefreshToken($user);

        return response()
            ->json([
                'status' => 200,
                'data' => [
                    'user' => $user->makeHidden(['password', 'refresh_token']),
                    ...$tokens,
                ],
                'message' => 'User logged in successfully',
            ])
            ->cookie(
                'accessToken',
                $tokens['accessToken'],
                0,
                '/',
                null,
                false,
                true,
                false,
                'Lax'
            )
            ->cookie(
                'refreshToken',
                $tokens['refreshToken'],
                0,
                '/',
                null,
                false,
                true,
                false,
                'Lax'
            );
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $user = $request->user;

        $user->update(['refresh_token' => null]);

        return response()
            ->json([
                'status'  => 200,
                'message' => 'User logged out successfully',
            ])
            ->withoutCookie('accessToken')
            ->withoutCookie('refreshToken');
    }
}
