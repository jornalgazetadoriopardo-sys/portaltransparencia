<?php

namespace App\Http\Controllers\Portal;

use App\Enums\CategoriaDocumento;
use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Parceria;
use Illuminate\Contracts\View\View;

class PrestacaoContasController extends Controller
{
    /** Categorias que compõem a prestação de contas anual da associação. */
    public const CATEGORIAS = [
        CategoriaDocumento::Balanco,
        CategoriaDocumento::ParecerConselho,
        CategoriaDocumento::Auditoria,
        CategoriaDocumento::RelatorioAtividades,
        CategoriaDocumento::PrestacaoContas,
        CategoriaDocumento::Ata,
    ];

    public function __invoke(): View
    {
        $documentos = Documento::query()
            ->publicados()
            ->whereIn('categoria', self::CATEGORIAS)
            ->orderByDesc('ano')
            ->latest('data_publicacao')
            ->get()
            ->groupBy(fn (Documento $d) => $d->ano ?? $d->data_publicacao->year);

        return view('portal.prestacao-contas', [
            'documentosPorAno' => $documentos,
            'parcerias' => Parceria::query()->publicadas()->orderByDesc('data_assinatura')->get(),
            'atualizadoEm' => max(Documento::max('updated_at'), Parceria::max('updated_at')),
        ]);
    }
}
