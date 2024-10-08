<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('cliente')->check()) {
            return response()->json(['error' => 'Verifique o Token'], 401);
        }

        $tokenArmazenado = Auth::guard('cliente')->user()->token_acesso;

        $tokenRecebido = $request->bearerToken();

        if ($tokenArmazenado !== $tokenRecebido) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}
