<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    protected function respondSuccess(mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
        ], $code);
    }

    protected function respondCreated(mixed $data = null): JsonResponse
    {
        return $this->respondSuccess($data, 201);
    }

    protected function respondMessage(string $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $code);
    }

    protected function respondError(string $message = 'Erro inesperado', int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error'   => $message,
        ], $code);
    }
}
