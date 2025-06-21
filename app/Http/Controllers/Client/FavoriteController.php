<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseController;
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
     *     path="/api/client/favorites/plus",
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
    public function plus(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);
        $clientId = $request->user()->id;

        $result = $this->service->incrementFavorite($clientId, $request->product_id);

        return $result
            ? $this->respondSuccess($result)
            : $this->respondError('Produto inválido', 422);
    }

    /**
     * @OA\Post(
     *     path="/api/client/favorites/minus",
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
    public function minus(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);
        $clientId = $request->user()->id;

        $success = $this->service->decrementFavorite($clientId, $request->product_id);

        return $success
            ? $this->respondMessage('Quantidade atualizada/removida')
            : $this->respondError('Favorito não encontrado', 404);
    }
}
