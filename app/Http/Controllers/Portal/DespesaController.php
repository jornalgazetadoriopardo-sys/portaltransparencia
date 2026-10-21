<?php

namespace App\Http\Controllers\Portal;

use App\Enums\CategoriaDespesa;
use App\Http\Controllers\Controller;
use App\Models\Despesa;
use App\Models\Parceria;
use App\Support\Anos;
use App\Support\ExportadorCsv;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DespesaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);
        $consulta = $this->consulta($filtros);

        $porCategoria = (clone $consulta)->reorder()
            ->selectRaw('categoria, SUM(valor) as total')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->get()
            ->map(fn (Despesa $d) => ['rotulo' => $d->categoria->getLabel(), 'total' => (float) $d->total]);

        return view('portal.despesas', [
            'filtros' => $filtros,
            'despesas' => $consulta->with('parceria')->paginate(25)->withQueryString(),
            'total' => (float) (clone $consulta)->reorder()->sum('valor'),
            'porCategoria' => $porCategoria,
            'anos' => Anos::disponiveis(Despesa::min('data'), Despesa::max('data')),
            'categorias' => CategoriaDespesa::opcoes(),
            'parcerias' => Parceria::query()->publicadas()->orderByDesc('data_assinatura')->get(),
            'atualizadoEm' => Despesa::max('updated_at'),
        ]);
    }

    public function exportar(Request $request)
    {
        $registros = $this->consulta($this->filtros($request))->with('parceria')->lazy();

        return ExportadorCsv::responder($request, 'despesas', [
            'data' => 'Data',
            'categoria' => 'Categoria',
            'favorecido' => 'Favorecido',
            'documento' => 'CNPJ/CPF',
            'documento_fiscal' => 'Documento fiscal',
            'descricao' => 'Descrição',
            'parceria' => 'Parceria',
            'valor' => 'Valor (R$)',
        ], $registros, fn (Despesa $d) => [
            'data' => $d->data->format('d/m/Y'),
            'categoria' => $d->categoria->getLabel(),
            'favorecido' => $d->favorecido,
            'documento' => $d->favorecido_documento_publico,
            'documento_fiscal' => $d->documento_fiscal,
            'descricao' => $d->descricao,
            'parceria' => $d->parceria?->identificacao,
            'valor' => (float) $d->valor,
        ]);
    }

    /**
     * @return array{ano: ?int, categoria: ?string, parceria: ?int, busca: ?string}
     */
    private function filtros(Request $request): array
    {
        $dados = $request->validate([
            'ano' => ['nullable', 'integer', 'between:1900,2100'],
            'categoria' => ['nullable', Rule::enum(CategoriaDespesa::class)],
            'parceria' => ['nullable', 'integer'],
            'busca' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'ano' => isset($dados['ano']) ? (int) $dados['ano'] : null,
            'categoria' => $dados['categoria'] ?? null,
            'parceria' => isset($dados['parceria']) ? (int) $dados['parceria'] : null,
            'busca' => $dados['busca'] ?? null,
        ];
    }

    private function consulta(array $filtros): Builder
    {
        return Despesa::query()
            ->when($filtros['ano'], fn (Builder $q, int $ano) => $q->whereYear('data', $ano))
            ->when($filtros['categoria'], fn (Builder $q, string $categoria) => $q->where('categoria', $categoria))
            ->when($filtros['parceria'], fn (Builder $q, int $id) => $q->where('parceria_id', $id))
            ->when($filtros['busca'], fn (Builder $q, string $busca) => $q->where(fn (Builder $q) => $q
                ->where('descricao', 'like', '%'.$busca.'%')
                ->orWhere('favorecido', 'like', '%'.$busca.'%')
                ->orWhere('documento_fiscal', 'like', '%'.$busca.'%')))
            ->orderByDesc('data')
            ->orderByDesc('id');
    }
}
