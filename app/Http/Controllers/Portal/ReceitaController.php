<?php

namespace App\Http\Controllers\Portal;

use App\Enums\FonteReceita;
use App\Http\Controllers\Controller;
use App\Models\Parceria;
use App\Models\Receita;
use App\Support\Anos;
use App\Support\ExportadorCsv;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReceitaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);
        $consulta = $this->consulta($filtros);

        $porFonte = (clone $consulta)->reorder()
            ->selectRaw('fonte, SUM(valor) as total')
            ->groupBy('fonte')
            ->orderByDesc('total')
            ->get()
            ->map(fn (Receita $r) => ['rotulo' => $r->fonte->getLabel(), 'total' => (float) $r->total]);

        return view('portal.receitas', [
            'filtros' => $filtros,
            'receitas' => $consulta->with('parceria')->paginate(25)->withQueryString(),
            'total' => (float) (clone $consulta)->reorder()->sum('valor'),
            'porFonte' => $porFonte,
            'anos' => Anos::disponiveis(Receita::min('data'), Receita::max('data')),
            'fontes' => FonteReceita::opcoes(),
            'parcerias' => Parceria::query()->publicadas()->orderByDesc('data_assinatura')->get(),
            'atualizadoEm' => Receita::max('updated_at'),
        ]);
    }

    public function exportar(Request $request)
    {
        $registros = $this->consulta($this->filtros($request))->with('parceria')->lazy();

        return ExportadorCsv::responder($request, 'receitas', [
            'data' => 'Data',
            'fonte' => 'Fonte',
            'descricao' => 'Descrição',
            'parceria' => 'Parceria',
            'valor' => 'Valor (R$)',
        ], $registros, fn (Receita $r) => [
            'data' => $r->data->format('d/m/Y'),
            'fonte' => $r->fonte->getLabel(),
            'descricao' => $r->descricao,
            'parceria' => $r->parceria?->identificacao,
            'valor' => (float) $r->valor,
        ]);
    }

    /**
     * @return array{ano: ?int, fonte: ?string, parceria: ?int, busca: ?string}
     */
    private function filtros(Request $request): array
    {
        $dados = $request->validate([
            'ano' => ['nullable', 'integer', 'between:1900,2100'],
            'fonte' => ['nullable', Rule::enum(FonteReceita::class)],
            'parceria' => ['nullable', 'integer'],
            'busca' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'ano' => isset($dados['ano']) ? (int) $dados['ano'] : null,
            'fonte' => $dados['fonte'] ?? null,
            'parceria' => isset($dados['parceria']) ? (int) $dados['parceria'] : null,
            'busca' => $dados['busca'] ?? null,
        ];
    }

    private function consulta(array $filtros): Builder
    {
        return Receita::query()
            ->when($filtros['ano'], fn (Builder $q, int $ano) => $q->whereYear('data', $ano))
            ->when($filtros['fonte'], fn (Builder $q, string $fonte) => $q->where('fonte', $fonte))
            ->when($filtros['parceria'], fn (Builder $q, int $id) => $q->where('parceria_id', $id))
            ->when($filtros['busca'], fn (Builder $q, string $busca) => $q->where('descricao', 'like', '%'.$busca.'%'))
            ->orderByDesc('data')
            ->orderByDesc('id');
    }
}
