<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validation  = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validation->fails()) {
            return new AuthResource(
                ['errors' => $validation->errors()],
                false,
                'Validation failed'
            );
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Throwable $th) {
            $user->delete();
            return new AuthResource(null, false, 'Token generation failed: ' . $th->getMessage());
        }

        return new AuthResource(
            [
                'token' => $token,
                'user' => new UserResource($user)
            ],
            true,
            'Register & auto-login successful'
        );
    }


    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return new AuthResource(
                ['errors' => $validation->errors()],
                false,
                'Validation failed'
            );
        }

        $crendentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($crendentials)) {
            return new AuthResource(['error' => 'Unauthorized'], false, 'Login failed');
        }

        $user = JWTAuth::user();

        return new AuthResource(
            [
                'token' => $token,
                'user' => new UserResource($user)
                // 'user' => new UserResource(auth()->user())
            ],
            true,
            'Login successful'
        );
    }
}
