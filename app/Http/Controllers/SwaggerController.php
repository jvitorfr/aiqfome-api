<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="aiqfome API",
 *     version="1.0.0",
 *     description="Documentação da API RESTful para o desafio técnico do aiqfome."
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerController extends Controller
{

}
