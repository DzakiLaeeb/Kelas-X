<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    /**
     * Login user and create token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([
                'message' => 'Login Gagal',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Login Gagal',
                'token' => null,
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Check if user is active
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Akun tidak aktif',
                'token' => null,
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        // Generate new API token
        $token = Str::random(40);
        $user->api_token = $token;
        $user->save();

        // Prepare user data for response
        $userData = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'level' => $user->level,
            'status' => $user->status,
            'api_token' => $token
        ];

        return response()->json([
            'message' => 'Login Berhasil',
            'token' => $token,
            'data' => $userData
        ], Response::HTTP_OK);
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'level' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([
                'message' => 'Registrasi Gagal',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'status' => $request->status,
            'api_token' => Str::random(40)
        ]);

        // Prepare user data for response
        $userData = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'level' => $user->level,
            'status' => $user->status,
            'api_token' => $user->api_token
        ];

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'token' => $user->api_token,
            'data' => $userData
        ], Response::HTTP_CREATED);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        
        // Remove the API token
        $user->api_token = null;
        $user->save();

        return response()->json([
            'message' => 'Logout Berhasil'
        ], Response::HTTP_OK);
    }
}
