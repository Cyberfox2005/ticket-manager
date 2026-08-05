<?php

namespace App\Modules\Auth\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $result = $this->authService->login($validated);

        return $this->successResponse($result, 'Login successful');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'device_name' => 'required',
        ]);

        $result = $this->authService->register($validated);

        return $this->successResponse($result, 'User registered successfully', 201);
    }

    public function user(Request $request)
    {
        return $this->successResponse($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
