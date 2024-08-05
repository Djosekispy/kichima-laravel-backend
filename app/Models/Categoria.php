<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Categoria extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'categorias';
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
    ];
}
