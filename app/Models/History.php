<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'historico';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_pedido',
        'descricao',
    ];



}
