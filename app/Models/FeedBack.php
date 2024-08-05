<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;

class FeedBack extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'feedback';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_produto',
        'nome_comentador',
        'conteudo',
        'estrelas',
        'foto'
    ];
}
