<?php

namespace App\Models;

use App\Enums\CategoriaDespesa;
use App\Support\Formatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'data', 'categoria', 'parceria_id', 'favorecido', 'favorecido_documento',
    'documento_fiscal', 'descricao', 'valor', 'comprovante',
])]
class Despesa extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'date',
            'categoria' => CategoriaDespesa::class,
            'valor' => 'decimal:2',
        ];
    }

    public function parceria(): BelongsTo
    {
        return $this->belongsTo(Parceria::class);
    }

    /**
     * Documento do favorecido como deve aparecer no portal (CPF mascarado — LGPD).
     */
    public function getFavorecidoDocumentoPublicoAttribute(): ?string
    {
        return Formatar::documentoPublico($this->favorecido_documento);
    }
}
