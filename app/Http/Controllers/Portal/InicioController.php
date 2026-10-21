<?php

namespace App\Http\Controllers\Portal;

use App\Enums\SituacaoParceria;
use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Despesa;
use App\Models\Dirigente;
use App\Models\Documento;
use App\Models\Parceria;
use App\Models\Receita;
use Illuminate\Contracts\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        $ano = now()->year;

        return view('portal.inicio', [
            'ano' => $ano,
            'receitasAno' => (float) Receita::query()->whereYear('data', $ano)->sum('valor'),
            'despesasAno' => (float) Despesa::query()->whereYear('data', $ano)->sum('valor'),
            'parceriasVigentes' => Parceria::query()->publicadas()->where('situacao', SituacaoParceria::EmExecucao)->count(),
            'recursosParcerias' => (float) Parceria::query()->publicadas()->where('situacao', SituacaoParceria::EmExecucao)->sum('valor_total'),
            'ultimosDocumentos' => Documento::query()->publicados()->latest('data_publicacao')->limit(5)->get(),
            'ultimasParcerias' => Parceria::query()->publicadas()->latest('data_assinatura')->limit(3)->get(),
            'contagem' => [
                'institucional' => Dirigente::query()->where('ativo', true)->count(),
                'parcerias' => Parceria::query()->publicadas()->count(),
                'receitas' => Receita::query()->count(),
                'despesas' => Despesa::query()->count(),
                'compras' => Compra::query()->count(),
                'documentos' => Documento::query()->publicados()->count(),
            ],
        ]);
    }
}
