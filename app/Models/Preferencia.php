<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Preferencia extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'preferencias';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id_cliente',
        'categorias',
    ];
}
