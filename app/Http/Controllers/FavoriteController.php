<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseController;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends BaseController
{
    public function __construct(
        protected ProductService $service
    )
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->service->getFavoritesByClientId();
        return $this->respondSuccess($products);
    }

    public function plus(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);

        $result = $this->service->incrementFavorite($request->user()->id, $request->product_id);

        return $result
            ? $this->respondSuccess($result)
            : $this->respondError('Produto inválido', 422);
    }

    public function minus(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|integer']);

        $success = $this->service->decrementFavorite($request->user()->id, $request->product_id);

        return $success
            ? $this->respondMessage('Quantidade atualizada/removida')
            : $this->respondError('Favorito não encontrado', 404);
    }


}
