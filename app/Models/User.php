<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable;
    protected $connection = 'mongodb';
    protected $guarded = [];
    protected $collection = 'clientes';
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nome_completo',
        'email',
        'password',
        'endereco',
        'genero',
        'telefone',
        'token_acesso',
        'codigo_reposicao',
        'foto'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token_acesso',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];



    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna a chave primária do usuário
    }

    public function getJWTCustomClaims()
    {
        return []; // Pode adicionar quaisquer claims personalizados que desejar incluir no token JWT
    }
}
