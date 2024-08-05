<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendedorController;
use App\Http\Middleware\VerificarContactos;
use App\Http\Middleware\verificarIdVendedor;
use App\Http\Middleware\VerificarVendedorContactos;
use App\Http\Middleware\VerifyAccount;
use App\Http\Middleware\verifyAdminToken;
use App\Http\Middleware\VerifyClientId;
use App\Http\Middleware\VerifyEmail;
use App\Http\Middleware\VerifyToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\Guard;




Route::prefix('admin')->group(function () {
    Route::post('/singin',[AdminAuthController::class, 'singin']);
    // Gestão de Vendedores
    Route::middleware([verifyAdminToken::class])->group(function () {
        Route::post('/sms/vendedor/{id}',[AdminAuthController::class, 'enviarEmailparaVendedor']);
        Route::controller(PedidoController::class)->group( function () {
                Route::prefix('pedido')->group( function () {
                    Route::put('/alterar/{id}','alterarEstadoDoPedido');
                    Route::get('/ver-detalhes/{id}','mostarDetalhes');
                    Route::get('/ver-pedidos-usuario/{id}','TodosPedidos');
                    Route::get('/listar','listarPedido');
                    Route::get('/pendetes','pedidosPendentes');
                    Route::get('/confirmados','pedidosConfirmados');
                    Route::get('/pagos','pedidosPagos');
                    Route::get('/por-vendedor/{id}','pedidosPorVendedor');
                });
            });
        Route::prefix('vendedor')->group( function () {
            Route::controller(VendedorController::class)->group(function () {
                Route::post('/salvar','create')->middleware([VerificarVendedorContactos::class]);
                Route::get('/lista','index');
                Route::delete('/apagar/{id}','delete');
                Route::put('/actualizar/{id}','update');
                Route::get('/ver/{id}','show');
            });
        });
    });
});


Route::prefix('cliente')->group( function(){
    Route::controller(ClienteController::class)->group(function(){
         Route::post('/update/{id}','update');
          Route::put('/contact/{id}','contact');
          Route::get('/sms','showSMS');
        Route::middleware(VerificarContactos::class)->group(function (){
            Route::post('/salvar','store');
            Route::get('/show/{id}','show');
            Route::post('/update-image/{id}','addImage');
            Route::post('/update-password/{id}','updatePassword');
        });
    });

     //Autenticação
Route::controller(AutenticacaoController::class)->group(function () {
        Route::post('/login','login')->middleware(VerifyEmail::class);
        Route::post('/repor/confirmar-senha','ConfirmarCodigoDeReposicao');
        Route::post('/repor/nova-senha','definirNovaSenha');
        Route::get('/sair','sair');

        Route::middleware(VerifyAccount::class)->group(function (){
            Route::post('/repor/senha/enviar','enviarCodigoDeReposicaoDeSenha');
        });
    });
});



//Gestão de Produtos
Route::controller(ProdutoController::class)->group(function () {
    Route::prefix('produto')->group(function (){
        Route::get('/listar','index');
        Route::get('/categorias','categories');
        Route::get('/feed/{id}','feedPersonalizado');
          Route::get('/pesquisar/{busca}','pesquisarProduto');
        Route::get('/ver/{id}','show');
        Route::post('/adicionar-favorito/{id}','adicionarAosFavoritos');
          Route::post('/feedBack/{id}', 'enviarFeedBack');
    Route::get('/ver-feedback/{id}','verFeedBack');
     Route::put('/actualizar/{id}','update');
            Route::post('/actualizar-imagem/{id}','addImage');
            Route::delete('/deletar/{id}','destroy');
        Route::post('/salvar','store')->middleware([verifyAdminToken::class,verificarIdVendedor::class]);
        Route::middleware([verifyAdminToken::class])->group(function () {
            Route::put('/actualizar/{id}','update');
            Route::post('/actualizar-imagem/{id}','addImage');
            Route::delete('/deletar/{id}','destroy');
        });
    });
});

Route::middleware(VerifyToken::class)->group( function () {


    Route::controller(CarrinhoController::class)->group(function () {
        Route::prefix('carrinho')->group( function () {
            Route::post('/adicionar','adicionarItem');
            Route::get('/ver','getcarrinho');
        });

    Route::prefix('pedido')->group( function () {
        Route::post('/pagamento/{id}',[PagamentoController::class,'pagar']);

        Route::controller(PedidoController::class)->group( function () {
            Route::post('/enviar/{id}','fazerPedido')->middleware([VerifyClientId::class]);
            Route::get('/ver-detalhes/{id}','mostarDetalhes');
            Route::get('/ver-meus-pedidos/{id}','TodosPedidos');

        });
    });

    });





});
