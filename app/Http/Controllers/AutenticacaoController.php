<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AutenticacaoController extends Controller
{
    protected $email;
    public function __construct(EnviarEmail $email)
    {
        $this->email = $email;
    }
    public function login(Request $request)
    {
        try {

            $credentials = $request->validate(
                [
                    "email" => "required|email",
                    "password" => "required|string"
                ]
            );
            if (Auth::guard('cliente')->attempt($credentials)) {
                $user = Auth::guard('cliente')->user();
                $token = JWTAuth::fromUser($user);
                 User::where('_id', $user->_id)->update(['token_acesso' => $token]);

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


            public function enviarCodigoDeReposicaoDeSenha(Request $request){
                try {
                    $clienteEmail = $request->input('email');
                    $cliente = User::where('email', $clienteEmail)->first();
                    $codigo_reposicao = rand(100000, 999999);
                    User::where('email', $clienteEmail)->update(['codigo_reposicao' => $codigo_reposicao]);
                    $msg = "Saudações! {$cliente->nome_completo}, por favor, use este código para repor sua senha: {$codigo_reposicao}";
                     $this->email->sendEmail($cliente->nome_completo,'Reposição de Senha',$msg,$cliente->email);
                   
                    return response()->json(['sms' => 'Verifique sua caixa de email'],200);

                } catch (\Throwable $th) {
                    return response()->json(['error' => 'Erro ao enviar Email, tente mais tarde'],500);
                }
            }

            public function ConfirmarCodigoDeReposicao(Request $request){
                try {
                    $clienteEmail = $request->input('email');
                    $codigo_reposicao = $request->input('codigo_reposicao');
                    $cliente = User::where('email', $clienteEmail)->first();

                    if($cliente->codigo_reposicao !=  $codigo_reposicao) return response()->json(['error' => 'Código Inválido'],401);

                    return response()->json(['sms' => 'Código Confirmado'],200);

                } catch (\Throwable $th) {
                    return response()->json(['error' => 'Erro ao verificar código, tente mais tarde'.$th->getMessage()],500);
                }
            }

            public function definirNovaSenha(Request $request){
                try {
                    $clienteEmail = $request->input('email');
                    $nova_senha = Hash::make($request->input('nova_senha'));
                    $cliente = User::where('email', $clienteEmail)->first();
                    User::where('email', $clienteEmail)->update(['password' => $nova_senha]);
                    return response()->json(['sms' => 'Senha alterada com sucesso'],200);
                } catch (\Throwable $th) {
                    return response()->json(['error' => 'Erro ao tentar alterar senha, tente mais tarde'.$th->getMessage()],500);
                }
            }

            public function sair(){
                try {
                    JWTAuth::invalidate(JWTAuth::getToken());
                    return response()->json(['message' => 'Logout feito com sucesso'], 200);
                } catch (JWTException $e) {
                    return response()->json(['error' => 'Erro ao fazer logout'], 500);
                }

            }

}
