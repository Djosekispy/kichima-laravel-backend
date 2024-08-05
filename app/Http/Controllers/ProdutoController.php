<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedBackRequest;
use App\Http\Requests\ProdutoRequest;
use App\Models\FeedBack;
use App\Models\Preferencia;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Query\Builder;

class ProdutoController extends Controller
{
     // Método para armazenar um novo produto
    public function store(ProdutoRequest $request)
    {
        try {
            $data = $request->validationData();
            $data['imagens'] = [$this->uploadFile($request,$data['id_vendedor'],'imagens')];
            $data['categoria'] =  [$request->categoria];
             Produto::create($data);
            return response()->json([
                "message" => "Produto salvo com sucesso"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => "Erro ao tentar salvar produto: {$th->getMessage()}"
            ], 500);
        }
    }

    public function uploadFile($request, $user,$type)
    {
        $vendedorFolder = $user;
        $folderPath = "uploads/{$vendedorFolder}/{$type}";
        $fileName = time().'.'.$request->$type->extension();

        $request->$type->move(public_path($folderPath), $fileName);

        $fullpath = "$folderPath/$fileName";
        return $fullpath;
    }
    public function addImage(Request $request, $id){
        try {
            $produto = Produto::where('_id', $id)->first();
            if(!$produto)return response()->json(['error' => 'Produto Inexistente'],404);
            $imagens[] = $this->uploadFile($request,$id,'imagens'); 
        if(count([$produto['imagens']]) > 0){
         foreach($produto['imagens'] as $key => $prod) {
                $imagens[] = $prod;
            }
         }
 
            $data['imagens'] = $imagens;
            $produto->update($data);
            $produto->save();
            return response()->json([
                "message" => "Salvo com sucesso"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => "Erro ao tentar salvar imagem: ".$th->getMessage()
            ], 500);
        }

    }


    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos, 200);
    }


    public function show(string $id)
    {
        $produto = Produto::where('_id',$id)->first();
        if (!$produto) {
            return response()->json(["error" => "Produto não encontrado"], 404);
        }
        return response()->json($produto, 200);
    }


    public function destroy(string $id)
    {
        $produto = Produto::where('_id',$id)->first();
        if (!$produto) {
            return response()->json(["error" => "Produto não encontrado"], 404);
        }
        $produto->delete();
        return response()->json(["message" => "Produto deletado com sucesso"], 200);
    }

    public function update(ProdutoRequest $request, string $id)
    {
        try {
            $produto = Produto::where('_id',$id)->first();
            if (!$produto) {
                return response()->json(["error" => "Produto não encontrado"], 404);
            }

            $data = $request->validated();
           $data['categoria'] =  [$request->categoria];
            $produto->update($data);

            return response()->json(["message" => "Produto atualizado com sucesso"], 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Erro ao tentar atualizar produto: {$th->getMessage()}"], 500);
        }
    }

    public function adicionarAosFavoritos(Request $request, string $id)
    {
        $produto = Produto::where('_id',$id)->first();
        if (!$produto) {
            return response()->json(["error" => "Produto não encontrado"], 404);
        }

        $data['id_cliente'] = $request->userId;
        $data['categorias'] = $produto['categoria'];
        $preferencia = Preferencia::where('id_cliente',$data['id_cliente'])->first();

        if(Preferencia::where('id_cliente',$data['id_cliente'])->first()){
            foreach($preferencia['categorias'] as $c => $key){
                $data['categorias'][] = $preferencia['categorias'][$c];
            }

            $dadoNaoDuplicados = array_unique($data['categorias']);
            Preferencia::where('id_cliente',$data['id_cliente'])->update(['categorias' => $dadoNaoDuplicados]);
            return response()->json(["message" => "Produto adicionado aos favoritos com sucesso"], 200);
        }

        Preferencia::create($data);
        return response()->json(["message" => "Produto adicionado aos favoritos com sucesso"], 200);
    }

    public function feedPersonalizado(string $id){

            $usuario = $id;

            $preferencias = Preferencia::where('id_cliente',$usuario)->first();
            if($preferencias){
                $categoriasPreferidas = $preferencias['categorias'];

                 $produtos = DB::connection('mongodb')
                            ->collection('produtos')
                            ->whereIn('categoria',$categoriasPreferidas )
                            ->get();

                if ($produtos->isEmpty()) {
                    return response()->json([
                        'message' => 'Não há produtos correspondentes às suas preferências',
                    ], 404);
                }

                return response()->json($produtos, 200);
            }

         return response()->json([
                        'message' => 'Ainda não há preferências suas definidas, tente adicionar produtos a sua lista',
                    ], 404);
    }


    public function pesquisarProduto(string $tituloCategoriaOuDescricao){
        $busca = DB::connection('mongodb')
                    ->collection('produtos')
                    ->where('nome', 'like', "%$tituloCategoriaOuDescricao%")
                    ->orWhere('descricao','like', "%$tituloCategoriaOuDescricao%")
                    ->orWhereIn('categoria', [$tituloCategoriaOuDescricao])
                    ->get();

        if ($busca->isEmpty()) {
            return response()->json([
                'message' => 'Não há produtos correspondentes à sua pesquisa',
            ], 404);
        }

        return response()->json($busca, 200);
    }

    public function enviarFeedBack(FeedBackRequest $request, string $id){
        $data = $request->validated();
        $data['id_produto'] = $id;
        FeedBack::create($data);
        return response()->json(['message' => 'FeedBack enviado com sucesso']);
    }

public function verFeedBack(string $id) {
    $data = FeedBack::where('id_produto', $id)->get();

    if ($data->isNotEmpty()) {
        return response()->json($data, 200);
    } else {
        return response()->json(['message' => 'No feedback found for the given product ID.'], 404);
    }
}

public function categories()
{
    $produtos = Produto::all(); 
    $categoriasUnicas = []; 

    foreach ($produtos as $produto) { 
        if(isset($produto->categoria)){
            foreach ($produto->categoria as $categorias) { 
            if (!in_array($categorias, $categoriasUnicas)) { 
                $categoriasUnicas[] = $categorias; 
            }
        }
        }
        
    }

    return response()->json($categoriasUnicas, 200); 
}



}
