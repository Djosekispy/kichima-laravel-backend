<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Models\Pedido;
use App\Models\Vendedor;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF;


class PedidoController extends Controller
{

    public function fazerPedido(PedidoRequest $request,$id){
        try {
            $data = $request->validated();
            $data['referencia'] = uuid_create(4);
            $data['estado'] = 'pendente';
            $data['id_cliente'] = $id;
            Pedido::create($data);
            return response()->json([
                'message' => 'Pedido Feito com sucesso'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao submeter pedido'-$th->getMessage()
            ],501);
        }
    }
    public function mostarDetalhes(string $id){
        $data  = Pedido::where('_id',$id)->first();
        if(!$data) return response()->json(['error' => 'Registro Não encontrado'],404);

        return response()->json($data,200);
    }

    public function TodosPedidos(string $id){
        $data  = Pedido::where('id_cliente',$id)->get();

        if(!$data) return response()->json(['error' => 'Sem Registro'],404);

        return response()->json($data,200);
    }
    
    public function alterarEstadoDoPedido(Request $request ,string $id){

        if(!Pedido::where('_id',$id)->first()) return response()->json(['error' => 'Sem Registro'],404);

        Pedido::where('_id', $id)->update(['estado' => $request->estado]);

        return response()->json([
            'message' => 'Estado do Produto Alterado com sucesso',
        ],200);
    }

    public function listarPedido(){
        $pedidos = Pedido::all();
        return response()->json($pedidos,200);
    }

    public function pedidosPendentes(){
        $pendentes = Pedido::where('estado','pendente')->get();
        if(empty($pendentes)) return response()->json(['error' => 'Nao existe nenhum pedido pendente'],200);

        return response()->json([
            'message' => 'Pedidos pendentes',
            'data' => $pendentes
        ],200);
    }

    public function pedidosConfirmados(){
        $confirmados = Pedido::where('estado','confirmado')->get();
        if(empty($confirmados)) return response()->json(['error' => 'Nao existe nenhum pedido confirmados'],200);

        return response()->json([
            'message' => 'Pedidos Confirmados',
            'data' => $confirmados
        ],200);
    }

    public function pedidosPagos(){
        $pago = Pedido::where('estado','pago')->get();
        if(empty($pago)) return response()->json(['error' => 'Nao existe nenhum pedido pago'],200);

        return response()->json([
            'message' => 'Pedidos Pagos',
            'data' => $pago
        ],200);
    }
public function pedidosPorVendedor(string $id){
    $pedidos = Pedido::all();
    $vendedorPedidos = [];
    for ($i=0; $i < count($pedidos); $i++) {
        $produto = $pedidos[$i]['carrinho']['produto'];
         for ($b=0; $b < count($produto); $b++) {

            if($produto[$b]['id_vendedor'] == $id){
                $vendedorPedidos[] = $produto[$b];
            }
        }
    }
    if(empty($vendedorPedidos))return response()->json(['error' => 'Vendedor sem Produtos'],404);

    return response()->json(
        [
            'message' => 'Produtos do Vendedor',
            'data' => $vendedorPedidos
        ]
    );
}

public function gerarPdf($id){
    $pedido = Pedido::where('id',$id)->first();
    
    $pdf = FacadePdf::loadView('gerarpdf',['pedido' => $pedido])->setPaper('a4', 'landscape');
    return $pdf->download('comprovativo.pdf');
}
}
