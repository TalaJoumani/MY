<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthServices;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected AuthServices $authService;
    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->only('email', 'password', 'fcm_token');
        return response()->json($this->authService->login($credentials));
    }
}
