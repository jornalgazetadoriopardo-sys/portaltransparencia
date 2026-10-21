<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['parceria_id', 'funcao', 'quantidade', 'carga_horaria_semanal', 'remuneracao_mensal', 'exercicio'])]
class ParceriaEquipe extends Model
{
    protected function casts(): array
    {
        return [
            'remuneracao_mensal' => 'decimal:2',
        ];
    }

    public function parceria(): BelongsTo
    {
        return $this->belongsTo(Parceria::class);
    }
}
