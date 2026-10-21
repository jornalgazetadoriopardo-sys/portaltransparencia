<?php

namespace App\Models;

use App\Enums\SituacaoParceria;
use App\Enums\SituacaoPrestacao;
use App\Enums\TipoParceria;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tipo', 'numero', 'orgao_concedente', 'objeto', 'data_assinatura', 'vigencia_inicio', 'vigencia_fim',
    'valor_total', 'valor_liberado', 'contrapartida', 'situacao', 'prestacao_situacao',
    'prestacao_data_prevista', 'prestacao_data_apresentacao', 'prestacao_prazo_analise_dias',
    'prestacao_resultado', 'arquivo_instrumento', 'arquivo_plano_trabalho', 'publicado',
])]
class Parceria extends Model
{
    protected function casts(): array
    {
        return [
            'tipo' => TipoParceria::class,
            'situacao' => SituacaoParceria::class,
            'prestacao_situacao' => SituacaoPrestacao::class,
            'data_assinatura' => 'date',
            'vigencia_inicio' => 'date',
            'vigencia_fim' => 'date',
            'prestacao_data_prevista' => 'date',
            'prestacao_data_apresentacao' => 'date',
            'valor_total' => 'decimal:2',
            'valor_liberado' => 'decimal:2',
            'contrapartida' => 'decimal:2',
            'publicado' => 'boolean',
        ];
    }

    public function equipe(): HasMany
    {
        return $this->hasMany(ParceriaEquipe::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function receitas(): HasMany
    {
        return $this->hasMany(Receita::class);
    }

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class);
    }

    public function scopePublicadas(Builder $query): void
    {
        $query->where('publicado', true);
    }

    public function getIdentificacaoAttribute(): string
    {
        return $this->tipo->getLabel().' nº '.$this->numero;
    }
}
