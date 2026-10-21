<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nome', 'cargo', 'orgao', 'mandato_inicio', 'mandato_fim', 'ordem', 'ativo'])]
class Dirigente extends Model
{
    protected function casts(): array
    {
        return [
            'mandato_inicio' => 'date',
            'mandato_fim' => 'date',
            'ativo' => 'boolean',
        ];
    }

    public function scopeAtivos(Builder $query): void
    {
        $query->where('ativo', true)->orderBy('orgao')->orderBy('ordem')->orderBy('nome');
    }
}
