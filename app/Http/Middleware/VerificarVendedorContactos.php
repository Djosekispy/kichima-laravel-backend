<?php

namespace App\Http\Middleware;

use App\Models\Vendedor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarVendedorContactos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $telefone = $request->input('telefone');
        $vendedorEmail = Vendedor::where('email', $email)->first();
        $vendedorTelefone = Vendedor::where('telefone', $telefone)->first();

        if ($vendedorEmail || $vendedorTelefone ) return response()->json(['error' => 'Vendedor Já cadastrado'], 401);

        return $next($request);
    }
}
