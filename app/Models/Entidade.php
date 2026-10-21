<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'nome', 'sigla', 'cnpj', 'data_fundacao', 'finalidade', 'endereco', 'cidade_uf', 'cep',
    'telefone', 'email', 'horario_atendimento', 'responsavel_transparencia', 'site', 'logo',
])]
class Entidade extends Model
{
    protected function casts(): array
    {
        return [
            'data_fundacao' => 'date',
        ];
    }

    /**
     * A entidade é um registro único; devolve-o (ou um modelo vazio antes do cadastro).
     */
    public static function atual(): self
    {
        return static::query()->first() ?? new static(['nome' => config('app.name')]);
    }
}
