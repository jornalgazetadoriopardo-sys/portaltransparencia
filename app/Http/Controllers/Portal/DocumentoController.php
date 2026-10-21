<?php

namespace App\Http\Controllers\Portal;

use App\Enums\CategoriaDocumento;
use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Support\ExportadorCsv;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);

        return view('portal.documentos', [
            'filtros' => $filtros,
            'documentos' => $this->consulta($filtros)->paginate(20)->withQueryString(),
            'anos' => Documento::query()->publicados()->whereNotNull('ano')->distinct()->orderByDesc('ano')->pluck('ano'),
            'categorias' => CategoriaDocumento::opcoes(),
            'atualizadoEm' => Documento::max('updated_at'),
        ]);
    }

    public function exportar(Request $request)
    {
        $registros = $this->consulta($this->filtros($request))->lazy();

        return ExportadorCsv::responder($request, 'documentos', [
            'categoria' => 'Categoria',
            'titulo' => 'Título',
            'ano' => 'Ano',
            'data_publicacao' => 'Data de publicação',
            'url' => 'Endereço do arquivo',
        ], $registros, fn (Documento $d) => [
            'categoria' => $d->categoria->getLabel(),
            'titulo' => $d->titulo,
            'ano' => $d->ano,
            'data_publicacao' => $d->data_publicacao->format('d/m/Y'),
            'url' => $d->url,
        ]);
    }

    /**
     * @return array{categoria: ?string, ano: ?int, busca: ?string}
     */
    private function filtros(Request $request): array
    {
        $dados = $request->validate([
            'categoria' => ['nullable', Rule::enum(CategoriaDocumento::class)],
            'ano' => ['nullable', 'integer', 'between:1900,2100'],
            'busca' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'categoria' => $dados['categoria'] ?? null,
            'ano' => isset($dados['ano']) ? (int) $dados['ano'] : null,
            'busca' => $dados['busca'] ?? null,
        ];
    }

    private function consulta(array $filtros): Builder
    {
        return Documento::query()
            ->publicados()
            ->when($filtros['categoria'], fn (Builder $q, string $c) => $q->where('categoria', $c))
            ->when($filtros['ano'], fn (Builder $q, int $ano) => $q->where('ano', $ano))
            ->when($filtros['busca'], fn (Builder $q, string $busca) => $q->where(fn (Builder $q) => $q
                ->where('titulo', 'like', '%'.$busca.'%')
                ->orWhere('descricao', 'like', '%'.$busca.'%')))
            ->orderByDesc('data_publicacao')
            ->orderByDesc('id');
    }
}
