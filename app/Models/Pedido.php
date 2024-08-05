<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Pedido extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'pedidos';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'referencia',
        'carrinho',
        'total',
        'estado',
        'tipo_compra',
        'endereco_entrega',
        'id_cliente',
    ];
}
