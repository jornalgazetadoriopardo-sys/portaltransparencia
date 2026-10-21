<?php

namespace App\Http\Controllers\Portal;

use App\Enums\ModalidadeCompra;
use App\Enums\SituacaoCompra;
use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Support\Anos;
use App\Support\ExportadorCsv;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompraController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);

        return view('portal.compras', [
            'filtros' => $filtros,
            'compras' => $this->consulta($filtros)->with('parceria')->paginate(20)->withQueryString(),
            'anos' => Anos::disponiveis(Compra::min('data_abertura'), Compra::max('data_abertura')),
            'modalidades' => ModalidadeCompra::opcoes(),
            'situacoes' => SituacaoCompra::opcoes(),
            'atualizadoEm' => Compra::max('updated_at'),
        ]);
    }

    public function exportar(Request $request)
    {
        $registros = $this->consulta($this->filtros($request))->with('parceria')->lazy();

        return ExportadorCsv::responder($request, 'compras', [
            'numero' => 'Número',
            'modalidade' => 'Modalidade',
            'data_abertura' => 'Data de abertura',
            'objeto' => 'Objeto',
            'situacao' => 'Situação',
            'fornecedor' => 'Fornecedor',
            'fornecedor_documento' => 'CNPJ/CPF',
            'valor' => 'Valor (R$)',
            'parceria' => 'Parceria',
        ], $registros, fn (Compra $c) => [
            'numero' => $c->numero,
            'modalidade' => $c->modalidade->getLabel(),
            'data_abertura' => $c->data_abertura->format('d/m/Y'),
            'objeto' => $c->objeto,
            'situacao' => $c->situacao->getLabel(),
            'fornecedor' => $c->fornecedor,
            'fornecedor_documento' => $c->fornecedor_documento_publico,
            'valor' => $c->valor !== null ? (float) $c->valor : null,
            'parceria' => $c->parceria?->identificacao,
        ]);
    }

    /**
     * @return array{ano: ?int, modalidade: ?string, situacao: ?string, busca: ?string}
     */
    private function filtros(Request $request): array
    {
        $dados = $request->validate([
            'ano' => ['nullable', 'integer', 'between:1900,2100'],
            'modalidade' => ['nullable', Rule::enum(ModalidadeCompra::class)],
            'situacao' => ['nullable', Rule::enum(SituacaoCompra::class)],
            'busca' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'ano' => isset($dados['ano']) ? (int) $dados['ano'] : null,
            'modalidade' => $dados['modalidade'] ?? null,
            'situacao' => $dados['situacao'] ?? null,
            'busca' => $dados['busca'] ?? null,
        ];
    }

    private function consulta(array $filtros): Builder
    {
        return Compra::query()
            ->when($filtros['ano'], fn (Builder $q, int $ano) => $q->whereYear('data_abertura', $ano))
            ->when($filtros['modalidade'], fn (Builder $q, string $m) => $q->where('modalidade', $m))
            ->when($filtros['situacao'], fn (Builder $q, string $s) => $q->where('situacao', $s))
            ->when($filtros['busca'], fn (Builder $q, string $busca) => $q->where(fn (Builder $q) => $q
                ->where('numero', 'like', '%'.$busca.'%')
                ->orWhere('objeto', 'like', '%'.$busca.'%')
                ->orWhere('fornecedor', 'like', '%'.$busca.'%')))
            ->orderByDesc('data_abertura')
            ->orderByDesc('id');
    }
}
