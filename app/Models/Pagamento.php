<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use mongoDB\Laravel\Eloquent\Model;


class Pagamento extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'pagamentos';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id_pedido',
        'total',
        'tipo',
        'comprovativo'
    ];
}
