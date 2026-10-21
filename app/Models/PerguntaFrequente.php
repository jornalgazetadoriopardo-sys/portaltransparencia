<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['pergunta', 'resposta', 'ordem', 'publicado'])]
class PerguntaFrequente extends Model
{
    protected $table = 'perguntas_frequentes';

    protected function casts(): array
    {
        return [
            'publicado' => 'boolean',
        ];
    }
}
