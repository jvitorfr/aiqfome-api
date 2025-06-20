<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthUserController extends BaseController
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondSuccess([
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->respondMessage('Logout realizado com sucesso');
    }

    public function me(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return $this->respondError('Não autenticado', 401);
        }

        return $this->respondSuccess($user);
    }

}
