<?php

namespace App\Http\Middleware;

use App\Models\Vendedor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class verificarIdVendedor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uuid = $request->input('id_vendedor');

        $checkIdVendedor = Vendedor::where('_id', $uuid)->exists();

        if (!$checkIdVendedor)return response()->json(['error' => 'Vendedor não cadastrado'], 409);

        return $next($request);
    }
}
