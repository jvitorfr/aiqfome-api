<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class FavoriteController extends BaseController
{
    public function __construct(
        protected ProductService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/api/admin/favorites/{clientId}",
     *     summary="Lista os produtos favoritos de um cliente (admin)",
     *     tags={"Admin - Favorites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="clientId",
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
        $products = $this->service->getFavoritesByClientId($clientId);
        return $this->respondSuccess($products);
    }
}
