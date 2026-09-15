<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        try {
            return $this->authRepository->register($request);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' => __("An error occurred.")], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $token = $this->authRepository->login($request);
            return $token;

        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['message' =>  __("An error occurred.")], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
