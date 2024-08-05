<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyClientId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientId = $request->input("id_cliente");
        $checkId = User::where('_id',$clientId)->first();
        if(!$checkId) return response()->json(['error' => 'Cliente inexistente'],404);
        return $next($request);
    }
}
