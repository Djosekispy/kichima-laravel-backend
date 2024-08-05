<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' =>       'string',
            'descricao' => 'string',
            'preco' =>      'numeric',
            'categoria' => 'string',
            'taxa_entrega' => 'numeric',
            'imagens' =>    'file',
            'quantidade' => 'numeric',
            'origem' =>     'string',
            'taxa_venda' => 'numeric',
            'id_vendedor' => 'string',
            'localizacao' => 'string'
        ];
    }
}
