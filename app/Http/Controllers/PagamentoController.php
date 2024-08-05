<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use App\Http\Controllers\PedidoController;

class PagamentoController extends Controller
{
    protected $email;
    protected $pagamento;
    public function __construct(EnviarEmail $email, PedidoController $pagamento)
    {
        $this->email = $email;
        $this->pagamento = $pagamento;
    }
public function pagar(Request $request, string $id){
    try {
        $data = $request->validate([
            'total' => 'required|string',
            'tipo' => 'required|string',
            'comprovativo' => 'required|string',
        ]);
        
        $pedido = Pedido::where('_id', $id)->first();

        if (!$pedido) {
            return response()->json(['erro' => 'Pagamento Recusado, Verifique o ID do pedido'], 404);
        }

        $data['id_pedido'] = $pedido->id;
        Pagamento::create($data);
        
        // Criar histórico de pagamento
        $history = [
            'id_pedido' => $id,
            'descricao' => 'Pagamento Feito com sucesso'
        ];
        History::create($history);

        // Enviar e-mail para cada vendedor
        foreach ($pedido->carrinho['product'] as $produto) {
            $vendedor = Vendedor::where('_id', $produto['id_vendedor'])->first();
            if ($vendedor) {
                $name = $vendedor->nome; // Nome do vendedor
                $title = "Produto Vendido com Sucesso"; // Título do e-mail
                $msg = "Olá $name, seu produto '{$produto['nome']}' foi vendido com sucesso."; // Mensagem do e-mail
                $this->email->sendEmail($name, $title, $msg, $vendedor->email);
            }
        }

        return response()->json(['message' => 'Pagamento Feito com sucesso'], 200);

    } catch (\Throwable $th) {
        return response()->json(['erro' => 'Erro ao processar pagamento, tente mais tarde ' . $th], 404);
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
}
