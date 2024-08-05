<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function create(Request $request){
        try {
            $data['nome'] = $request->nome;
          Categoria::create($data);

        return response()->json(["message" => "Cadastro Feito com Sucesso"],200);
      } catch (\Throwable $th) {
        return response()->json(["error" => "Erro ao Cadastrar Categoria".$th->getMessage()],500);

      }
    }
}
