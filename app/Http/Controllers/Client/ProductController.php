<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseController;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function __construct(
        protected ProductService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Lista todos os produtos da FakeStore",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="image", type="string", format="uri")
     *         ))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $products = $this->service->getAll();
        return $this->respondSuccess($products);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Busca um produto por ID",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do produto",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="image", type="string", format="uri")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->service->getById($id);

        if (!$product) {
            return $this->respondError('Produto não encontrado', 404);
        }

        return $this->respondSuccess($product);
    }

}
