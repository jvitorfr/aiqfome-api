<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class TestSwaggerController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/test-swagger",
     *     summary="Teste de documentação funcionando",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function test(): JsonResponse
    {
        return $this->respondMessage('Tudo certo com Swagger');
    }
}
