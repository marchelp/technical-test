<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    // User login with JWT token
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return $this->errorResponse('Email atau Password salah.', 401);
        }

        return $this->successResponse('Berhasil login.', [
            'token' => $token
        ]);
    }

    // Get user profile
    public function profile() {
        $user = auth()->guard('api')->user();

        return $this->successResponse('Berhasil login.', [
            'full_name' => $user->full_name,
            'email' => $user->email,
        ]);
    }

    // User logout
    public function logout() {
        auth()->guard('api')->logout();
        return $this->successResponse('Berhasil logout.');
    }
}
