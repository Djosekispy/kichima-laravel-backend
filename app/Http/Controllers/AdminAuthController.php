<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    protected $email;
    public function __construct(EnviarEmail $email)
    {
        $this->email = $email;
    }


    public function singin(Request $request)
    {
        try {

            $credentials = $request->validate(
                [
                    "email" => "required|string",
                    "password" => "required|string"
                ]
            );
            if (Auth::guard('administrador')->attempt($credentials)) {
                $user = Auth::guard('administrador')->user();
                $token = JWTAuth::fromUser($user);
                Admin::where('email', $user->email)->update(['token_acesso' => $token]);

                return response()->json([
                        'user' => $user,
                        'token' => $token
                ], 200);
            }
            
            return response()->json(['error' => 'Credenciais Incorrectas. Por favor verifique o seu E-mail ou Senha.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erro ao gerar token de autenticação.'], 500);
        }
    }

    public function enviarEmailparaVendedor(Request $request, string $id){
        try {
            $data = $request->validate([
                "titulo" => "required|string",
                "sms" => "required|string"
            ]);
            $vendedor = Vendedor::where('_id',$id)->first();
            if(isset($vendedor) and isset($vendedor->email)){
                $this->email->Enviar($data['titulo'],$data['sms'],$vendedor->email);
                return response()->json(['message' => 'E-mail enviado com sucesso'],200);
            }

            return response()->json(['error' => 'Vendedor/E-MAIL não existe'],404);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro no envio'.$th->getMessage()],404);
        }
    }
}
