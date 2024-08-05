<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Carrinho extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'carrinhos';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'produto',
        'id_cliente',
        'total',
        'categoria'
    ];
}
