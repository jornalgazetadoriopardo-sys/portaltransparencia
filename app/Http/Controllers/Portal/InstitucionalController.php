<?php

namespace App\Http\Controllers\Portal;

use App\Enums\CategoriaDocumento;
use App\Http\Controllers\Controller;
use App\Models\Dirigente;
use App\Models\Documento;
use App\Models\Entidade;
use Illuminate\Contracts\View\View;

class InstitucionalController extends Controller
{
    public function __invoke(): View
    {
        return view('portal.institucional', [
            'dirigentes' => Dirigente::query()->ativos()->get()->groupBy('orgao'),
            'documentos' => Documento::query()
                ->publicados()
                ->whereIn('categoria', [CategoriaDocumento::Estatuto, CategoriaDocumento::Regulamento, CategoriaDocumento::Certidao])
                ->orderBy('categoria')
                ->latest('data_publicacao')
                ->get(),
            'atualizadoEm' => max(Entidade::max('updated_at'), Dirigente::max('updated_at')),
        ]);
    }
}
