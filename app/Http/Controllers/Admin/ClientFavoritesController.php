<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Client;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ClientFavoritesController extends BaseController
{
    public function __construct(
        protected ProductService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/admin/clients/{client}/favorites",
     *     summary="Lista os produtos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"sanctum":{}}},
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
    public function index(Client $client): JsonResponse
    {
        $favorites = $this->service->getFavoritesByClientId($client->id);
        return $this->respondSuccess($favorites);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/clients/{client}/favorites",
     *     summary="Adiciona um produto aos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
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
     */
    public function store(Request $request, Client $client): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);

        $result = $this->service->addFavorite($client->id, $request->product_id);

        return $result
            ? $this->respondMessage('Produto favoritado com sucesso')
            : $this->respondError('Produto inválido ou já favoritado', 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/clients/{client}/favorites/{product}",
     *     summary="Remove um produto dos favoritos de um cliente (admin)",
     *     tags={"Admin - Gerenciar produtos dos clientes"},
     *     security={{"sanctum":{}}},
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
