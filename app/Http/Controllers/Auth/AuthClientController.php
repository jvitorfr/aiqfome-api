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

    /**
     * @OA\Post(
     *     path="/api/client/register",
     *     summary="Registra um novo cliente",
     *     tags={"Auth - Client"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="client", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/client/login",
     *     summary="Autentica um cliente",
     *     tags={"Auth - Client"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="expires_in", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/client/me",
     *     summary="Retorna o cliente autenticado",
     *     tags={"Auth - Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do cliente",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado"
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $client = Auth::guard('api')->user();

        if (!$client) {
            return $this->respondError('Não autenticado', 401);
        }

        return $this->respondSuccess($client);
    }

    /**
     * @OA\Post(
     *     path="/api/client/logout",
     *     summary="Realiza logout do cliente",
     *     tags={"Auth - Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->respondMessage('Logout realizado com sucesso');
    }
}
