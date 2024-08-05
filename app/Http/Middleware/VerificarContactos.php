<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarContactos
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

        $checkEmail = User::where('email', $email)->exists();
        $checkTelefone = User::where('telefone', $telefone)->exists();

        if ($checkEmail || $telefone) {
            return response()->json(['error' => 'Email ou Telefone já existe'], 409);
        }

        return $next($request);
    }
}
