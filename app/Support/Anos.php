<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class Anos
{
    /**
     * Anos disponíveis para filtro, do mais recente ao mais antigo.
     *
     * @return list<int>
     */
    public static function disponiveis(?string $menorData, ?string $maiorData): array
    {
        $atual = now()->year;
        $inicio = $menorData ? Carbon::parse($menorData)->year : $atual;
        $fim = max($atual, $maiorData ? Carbon::parse($maiorData)->year : $atual);

        return range($fim, $inicio);
    }
}
