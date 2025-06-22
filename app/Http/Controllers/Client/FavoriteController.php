<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseController;
use App\Models\Client;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class FavoriteController extends BaseController
{
    public function __construct(
        protected ProductService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/client/favorites",
     *     summary="Lista os produtos favoritos do client autenticado",
     *     tags={"Cliente - Produtos favoritos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de favoritos",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $clientId = $request->user()->id;

        $products = $this->service->getFavoritesByClientId($clientId);
        return $this->respondSuccess($products);
    }

    /**
     * @OA\Post(
     *     path="/api/client/favorites",
     *     summary="Incrementa (ou cria) um produto favorito",
     *     tags={"Cliente - Produtos favoritos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto favoritado",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Produto inválido"
     *     )
     * )
     */
    public function store(Request $request, Client $client): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);

        $result = $this->service->addFavorite($client->id, $request->product_id);

        return $result
            ? $this->respondSuccess($result)
            : $this->respondError('Produto inválido ou duplicado', 422);
    }

    /**
     * @OA\Delete(
     *     path="/api/client/favorites",
     *     summary="Decrementa ou remove um favorito",
     *     tags={"Cliente - Produtos favoritos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantidade atualizada ou item removido"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorito não encontrado"
     *     )
     * )
     */

    public function destroy(Client $client, int $productId): JsonResponse
    {
        $success = $this->service->removeFavorite($client->id, $productId);

        return $success
            ? $this->respondMessage('Removido com sucesso')
            : $this->respondError('Favorito não encontrado', 404);
    }
}
