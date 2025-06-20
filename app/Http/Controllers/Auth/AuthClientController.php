<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthClientController extends BaseController
{
    public function __construct(
        private readonly ClientService $clientService
    ) {}

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string'],
            'email'    => ['required', 'email', 'unique:clients,email'],
            'password' => ['required', 'string'],
        ]);

        $client = $this->clientService->create($data);

        $token = Auth::guard('api')->login($client);

        return $this->respondSuccess([
            'token'  => $token,
            'client' => $client,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->respondError('Credenciais inválidas', 401);
        }

        return $this->respondSuccess([
            'token'      => $token,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }

    public function me(): JsonResponse
    {
        $client = Auth::guard('api')->user();

        if (!$client) {
            return $this->respondError('Não autenticado', 401);
        }

        return $this->respondSuccess($client);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->respondMessage('Logout realizado com sucesso');
    }


}
