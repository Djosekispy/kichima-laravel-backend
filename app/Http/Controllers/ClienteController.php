<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarClienteRequest;
use App\Http\Requests\ClienteRequest;
use App\Models\Contacto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function store(ClienteRequest $request){
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            User::create($data);

            return response()->json(['message'=> 'Registro Feito Com Sucesso'],200);
        } catch (\Throwable $th) {
            return response()->json(['error'=> 'Erro ao fazer o Registro'.$th->getMessage()],501);
        }
    }

   public function contact(Request $request, string $id) {
    try {
        // Validação dos dados de entrada
        $request->validate([
            'titulo' => 'required|string',
            'descricao' => 'required|string',
        ]);

        $user = User::where('_id', $id)->first();
        if (!$user) {
            return response()->json(['error' => "Usuário não existe"], 401);
        }

        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'cliente' => $user,
        ];

        Contacto::create($data);

        return response()->json(['message' => 'Registro Feito Com Sucesso'], 201);
    } catch (\Throwable $th) {
        return response()->json(['error' => 'Erro ao fazer o Registro: ' . $th->getMessage()], 500);
    }
}


    public function show(string $id)
    {
        $user = User::where('_id', $id)->first();
        if(!$user) return response()->json(['error' => "Usuário não existe"],401);

        return response()->json([
            'user' => $user,
            'token' => $user->token

    ],200);
    }

    public function showSMS(Request $request)
    {
        $contacto = Contacto::all();
        return response()->json($contacto, 200);
    }

public function update(ActualizarClienteRequest $request, string $id)
{
    try {
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->update($data);

        return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Usuário não encontrado.'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao atualizar usuário. Tente mais tarde: ' . $e->getMessage()], 500);
    }
}


    public function addImage(Request $request, $id){
        try {
            $user = User::where('_id', $id)->first();
            $data['foto'] = $request->image;
            $user->update($data);
            $user->save();
            return response()->json([
                "message" => "Foto de Perfil salva com sucesso"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => "Erro ao tentar salvar imagem: ".$th->getMessage()
            ], 500);
        }

    }

    public function uploadFile($request, $user)
    {
        $vendedorFolder = $user;
        $folderPath = "uploads/{$vendedorFolder}/foto";
        $fileName = time().'.'.$request->foto->extension();

        $request->foto->move(public_path($folderPath), $fileName);

        $fullpath = "$folderPath/$fileName";
        return $fullpath;
    }

    public function updatePassword(Request $request,string $id){
        try {
            $user = User::where('_id', $id)->first();
            $data['password'] = Hash::make($request->password);
            $user->update($data);
            $user->save();
            return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user],200);
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Erro ao actualizar Usuario. Tente mais tarde', 'user' =>  $data],404);
            }
    }
}
