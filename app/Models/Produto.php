<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Produto extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'produtos';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'categoria',
        'taxa_entrega',
        'imagens',
        'quantidade',
        'origem',
        'taxa_venda',
        'id_vendedor',
        'localizacao'
    ];
}
