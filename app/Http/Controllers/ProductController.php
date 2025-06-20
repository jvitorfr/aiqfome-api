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
    )
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->service->getAll();
        return $this->respondSuccess($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->service->getById($id);

        if (!$product) {
            return $this->respondError('Produto não encontrado', 404);
        }

        return $this->respondSuccess($product);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $updated = $this->service->update($id, $request->all());

        if (!$updated) {
            return $this->respondError('Erro ao atualizar produto');
        }

        return $this->respondSuccess($updated);
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->service->delete($id);

        return $success
            ? $this->respondMessage('Produto removido com sucesso')
            : $this->respondError('Falha ao remover produto');
    }
}
