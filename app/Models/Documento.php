<?php

namespace App\Models;

use App\Enums\CategoriaDocumento;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['categoria', 'titulo', 'descricao', 'ano', 'data_publicacao', 'parceria_id', 'arquivo', 'publicado'])]
class Documento extends Model
{
    protected function casts(): array
    {
        return [
            'categoria' => CategoriaDocumento::class,
            'data_publicacao' => 'date',
            'publicado' => 'boolean',
        ];
    }

    public function parceria(): BelongsTo
    {
        return $this->belongsTo(Parceria::class);
    }

    public function scopePublicados(Builder $query): void
    {
        $query->where('publicado', true);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->arquivo);
    }

    public function getExtensaoAttribute(): string
    {
        return strtoupper(pathinfo($this->arquivo, PATHINFO_EXTENSION) ?: 'arquivo');
    }
}
