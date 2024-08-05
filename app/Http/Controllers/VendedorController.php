<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendedorRequest;
use Illuminate\Http\Request;
use App\Models\Vendedor;

class VendedorController extends Controller
{
    // Método para criar um novo vendedor
    public function create(VendedorRequest $request)
    {
        try {
            $vendedor = Vendedor::create($request->all());
            return response()->json($vendedor, 201);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Erro ao criar vendedor: " . $th->getMessage()], 500);
        }
    }

    // Método para retornar todos os vendedores
    public function index()
    {
        $vendedores = Vendedor::all();
        return response()->json($vendedores, 200);
    }

    // Método para retornar um vendedor específico
    public function show($id)
    {
        $vendedor = Vendedor::where('_id',$id)->first();
        if (!$vendedor) {
            return response()->json(["error" => "Vendedor não encontrado"], 404);
        }
        return response()->json($vendedor, 200);
    }

    // Método para atualizar um vendedor
    public function update(VendedorRequest $request, $id)
    {
        try {
            $vendedor = Vendedor::where('_id',$id)->first();
            if (!$vendedor) {
                return response()->json(["error" => "Vendedor não encontrado"], 404);
            }
            $vendedor->update($request->all());
            return response()->json($vendedor, 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Erro ao atualizar vendedor: " . $th->getMessage()], 500);
        }
    }

    // Método para deletar um vendedor
    public function delete($id)
    {
        $vendedor = Vendedor::where('_id',$id)->first();
        if (!$vendedor) {
            return response()->json(["error" => "Vendedor não encontrado"], 404);
        }
        $vendedor->delete();
        return response()->json(["message" => "Vendedor deletado com sucesso"], 200);
    }
}
