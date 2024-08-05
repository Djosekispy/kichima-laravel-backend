<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $vendedor = User::where('email', $email)->first();

        if (!$vendedor) {
            return response()->json(['error' => ' E-mail Inválido'], 401);
        }


        if (!Hash::check($password, $vendedor->password)) {
            return response()->json(['error' => 'Senha Incorrecta'], 401);
        }

/*
        if (!$vendedor->hasVerifiedEmail()) {
            return response()->json(['error' => 'Por favor Verifique a sua conta'], 403);
        }
        */
        return $next($request);
    }
}
