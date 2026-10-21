<?php

namespace App\Models;

use App\Enums\FonteReceita;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['data', 'fonte', 'parceria_id', 'descricao', 'valor', 'comprovante'])]
class Receita extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'date',
            'fonte' => FonteReceita::class,
            'valor' => 'decimal:2',
        ];
    }

    public function parceria(): BelongsTo
    {
        return $this->belongsTo(Parceria::class);
    }
}
