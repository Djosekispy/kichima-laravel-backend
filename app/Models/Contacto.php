<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'contactos';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cliente',
        'titulo',
        'descricao'
            ];
}
