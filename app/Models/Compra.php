<?php

namespace App\Models;

use App\Enums\ModalidadeCompra;
use App\Enums\SituacaoCompra;
use App\Support\Formatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'numero', 'modalidade', 'parceria_id', 'objeto', 'data_abertura', 'situacao',
    'fornecedor', 'fornecedor_documento', 'valor', 'arquivo',
])]
class Compra extends Model
{
    protected function casts(): array
    {
        return [
            'modalidade' => ModalidadeCompra::class,
            'situacao' => SituacaoCompra::class,
            'data_abertura' => 'date',
            'valor' => 'decimal:2',
        ];
    }

    public function parceria(): BelongsTo
    {
        return $this->belongsTo(Parceria::class);
    }

    public function getFornecedorDocumentoPublicoAttribute(): ?string
    {
        return Formatar::documentoPublico($this->fornecedor_documento);
    }
}
