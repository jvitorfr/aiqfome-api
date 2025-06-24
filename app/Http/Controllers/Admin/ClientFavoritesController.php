<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Client;
use App\Services\ClientService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Throwable;

class ClientFavoritesController extends BaseController
{
    public function __construct(
        protected ProductService $service,
        protected ClientService $clientService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/admin/clients/{client}/favorites",
     *     summary="Lista os produtos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de favoritos",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(int $clientId): JsonResponse
    {

        $client =  $this->clientService->find($clientId);

        if (!$client) {
            return $this->respondError('Cliente não encontrado', 404);
        }

        $favorites = $this->service->getFavoritesByClientId($client->id);
        return $this->respondSuccess($favorites);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/clients/{client}/favorites/{product}",
     *     summary="Adiciona um produto aos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="product",
     *          in="path",
     *          required=true,
     *         description="ID do produto favoritado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto favoritado com sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Produto inválido ou já favoritado"
     *     )
     * )
     * @throws Throwable
     */
    public function store(Client $client, int $productId): JsonResponse
    {
        $result = $this->service->addFavorite($client->id, $productId);

        return $result
            ? $this->respondMessage('Produto favoritado com sucesso')
            : $this->respondError('Produto inválido ou já favoritado', 422);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/clients/{client}/favorites/{product}",
     *     summary="Remove um produto dos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="ID do produto a ser removido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorito removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorito não encontrado"
     *     )
     * )
     */
    public function destroy(Client $client, int $product): JsonResponse
    {
        $success = $this->service->removeFavorite($client->id, $product);

        return $success
            ? $this->respondMessage('Favorito removido com sucesso')
            : $this->respondError('Favorito não encontrado', 404);
    }
}
