<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarrinhoRequest;
use App\Models\Carrinho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarrinhoController extends Controller
{
    private $carrinho;


    public function __construct()
    {
        $this->carrinho = Carrinho::where('id_cliente', Auth::guard('cliente')->user()->_id)->first();
    }


    public function adicionarItem(CarrinhoRequest $request)
    {
        try {
            $data = $request->validated();
            $clienteId = Auth::guard('cliente')->user()->_id;

            $meuCarrinho = Carrinho::where('id_cliente', $clienteId)->first();

            if (!$meuCarrinho) {
                $data['id_cliente'] = $clienteId;
                $data['produto'][] = $request->produto;
                $data['total'] = $request->produto['preco'];
                $data['categoria'][] = $request->produto['categoria'];
                Carrinho::create($data);
                return response()->json(['message' => 'adicionado com sucesso'], 200);
            }

            $this->carrinho['produto'][] = $request->produto;
            $this->carrinho['total'] += $request->produto['preco'];
            $this->carrinho['categoria'][] = $request->produto['categoria'];
            //$meuCarrinho->save();

            return response()->json([
                'message' => 'adicionado com sucesso',
                'carrinho' => $this->getcarrinho()
        ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao adicionar item: ' . $th->getMessage()], 500);
        }
    }


   // Remove um item do carrinho
   public function removerItem($produtoId)
   {
       if (isset($this->carrinho[$produtoId])) {
           unset($this->carrinho[$produtoId]);
       }
   }

   // Atualiza a quantidade de um item no carrinho
   public function atualizarQuantidade($produtoId, $novaQuantidade)
   {
       if (isset($this->carrinho[$produtoId])) {
           if ($novaQuantidade <= 0) {
               $this->removerItem($produtoId);
           } else {
               $this->carrinho[$produtoId] = $novaQuantidade;
           }
       }
   }

   // Retorna todos os carrinho do carrinho
   public function getcarrinho()
   {
       return $this->carrinho;
   }

   // Limpa o carrinho, removendo todos os carrinho
   public function limparCarrinho()
   {
       $this->carrinho = [];
   }

   // Calcula o total do carrinho
   public function calcularTotal()
   {
       $total = 0;
       foreach ($this->carrinho as $produtoId => $quantidade) {
           // Lógica para obter o preço do produto a partir do ID do produto
           // e multiplicar pelo quantidade
           // Aqui, é assumido que existe alguma lógica para obter o preço do produto
           $precoProduto = $this->obterPrecoProduto($produtoId);
           $total += $precoProduto * $quantidade;
       }
       return $total;
   }

   // Método fictício para obter o preço do produto a partir do ID do produto
   private function obterPrecoProduto($produtoId)
   {
       // Lógica para obter o preço do produto a partir do ID do produto
       // Este é apenas um exemplo fictício
       // Você pode substituir esta lógica com a lógica real do seu aplicativo
       // Exemplo: busca no banco de dados ou consulta a uma API externa
       $precos = [
           1 => 10.99,
           2 => 5.99,
           // Mais preços de produtos aqui...
       ];

       return $precos[$produtoId] ?? 0; // Se o ID do produto não existir, retorna 0
   }





}
