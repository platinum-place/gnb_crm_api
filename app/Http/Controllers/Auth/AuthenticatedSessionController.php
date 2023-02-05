<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $token = $user->createToken(Str::random(40))->plainTextToken;

        return response()->json([
            'user' => $request->user(),
            'token' => $token,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        Auth::guard("web")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
