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
    ) {}

    /**
     * @OA\Get(
     *     path="/api/client/products",
     *     summary="Lista todos os produtos da FakeStore",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     security={{"bearerAuth":{}}},
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
     *     path="/api/client/products/{id}",
     *     summary="Busca um produto por ID",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     security={{"bearerAuth":{}}},
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

    /**
     * @OA\Put(
     *     path="/api/client/products/{id}",
     *     summary="Atualiza um produto via FakeStore API",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="image", type="string", format="uri")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar produto"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $updated = $this->service->update($id, $request->all());

        if (!$updated) {
            return $this->respondError('Erro ao atualizar produto');
        }

        return $this->respondSuccess($updated);
    }

    /**
     * @OA\Delete(
     *     path="/api/client/products/{id}",
     *     summary="Remove um produto via FakeStore API",
     *     tags={"Cliente - Produtos (FAKESTORE)"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Falha ao remover produto"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->service->delete($id);

        return $success
            ? $this->respondMessage('Produto removido com sucesso')
            : $this->respondError('Falha ao remover produto');
    }
}
