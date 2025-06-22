<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('sanctum')->user();

        if (!$user instanceof User) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
