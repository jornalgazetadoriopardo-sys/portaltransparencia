<?php

namespace App\Http\Controllers\Portal;

use App\Enums\SituacaoParceria;
use App\Enums\TipoParceria;
use App\Http\Controllers\Controller;
use App\Models\Parceria;
use App\Support\Anos;
use App\Support\ExportadorCsv;
use App\Support\Formatar;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParceriaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->filtros($request);
        $consulta = $this->consulta($filtros);

        return view('portal.parcerias', [
            'filtros' => $filtros,
            'parcerias' => $consulta->paginate(15)->withQueryString(),
            'totais' => [
                'valor_total' => (float) (clone $consulta)->reorder()->sum('valor_total'),
                'valor_liberado' => (float) (clone $consulta)->reorder()->sum('valor_liberado'),
            ],
            'anos' => Anos::disponiveis(Parceria::min('data_assinatura'), Parceria::max('data_assinatura')),
            'tipos' => TipoParceria::opcoes(),
            'situacoes' => SituacaoParceria::opcoes(),
            'atualizadoEm' => Parceria::max('updated_at'),
        ]);
    }

    public function show(Parceria $parceria): View
    {
        abort_unless($parceria->publicado, 404);

        $parceria->load([
            'equipe' => fn ($q) => $q->orderByDesc('exercicio')->orderBy('funcao'),
            'documentos' => fn ($q) => $q->publicados()->latest('data_publicacao'),
        ]);

        return view('portal.parceria', [
            'parceria' => $parceria,
            'totalRecebido' => (float) $parceria->receitas()->sum('valor'),
            'totalGasto' => (float) $parceria->despesas()->sum('valor'),
        ]);
    }

    public function exportar(Request $request)
    {
        $registros = $this->consulta($this->filtros($request))->lazy();

        return ExportadorCsv::responder($request, 'parcerias', [
            'tipo' => 'Tipo de instrumento',
            'numero' => 'Número',
            'data_assinatura' => 'Data de assinatura',
            'orgao_concedente' => 'Órgão concedente',
            'objeto' => 'Objeto',
            'vigencia' => 'Vigência',
            'valor_total' => 'Valor total (R$)',
            'valor_liberado' => 'Valor liberado (R$)',
            'situacao' => 'Situação',
            'prestacao_situacao' => 'Situação da prestação de contas',
            'prestacao_data_prevista' => 'Prestação — data prevista',
            'prestacao_data_apresentacao' => 'Prestação — data de apresentação',
            'prestacao_prazo_analise_dias' => 'Prestação — prazo de análise (dias)',
            'prestacao_resultado' => 'Prestação — resultado',
        ], $registros, fn (Parceria $p) => [
            'tipo' => $p->tipo->getLabel(),
            'numero' => $p->numero,
            'data_assinatura' => $p->data_assinatura->format('d/m/Y'),
            'orgao_concedente' => $p->orgao_concedente,
            'objeto' => $p->objeto,
            'vigencia' => Formatar::data($p->vigencia_inicio).' a '.Formatar::data($p->vigencia_fim),
            'valor_total' => (float) $p->valor_total,
            'valor_liberado' => (float) $p->valor_liberado,
            'situacao' => $p->situacao->getLabel(),
            'prestacao_situacao' => $p->prestacao_situacao->getLabel(),
            'prestacao_data_prevista' => $p->prestacao_data_prevista?->format('d/m/Y'),
            'prestacao_data_apresentacao' => $p->prestacao_data_apresentacao?->format('d/m/Y'),
            'prestacao_prazo_analise_dias' => $p->prestacao_prazo_analise_dias,
            'prestacao_resultado' => $p->prestacao_resultado,
        ]);
    }

    /**
     * @return array{ano: ?int, tipo: ?string, situacao: ?string, busca: ?string}
     */
    private function filtros(Request $request): array
    {
        $dados = $request->validate([
            'ano' => ['nullable', 'integer', 'between:1900,2100'],
            'tipo' => ['nullable', Rule::enum(TipoParceria::class)],
            'situacao' => ['nullable', Rule::enum(SituacaoParceria::class)],
            'busca' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'ano' => isset($dados['ano']) ? (int) $dados['ano'] : null,
            'tipo' => $dados['tipo'] ?? null,
            'situacao' => $dados['situacao'] ?? null,
            'busca' => $dados['busca'] ?? null,
        ];
    }

    private function consulta(array $filtros): Builder
    {
        return Parceria::query()
            ->publicadas()
            ->when($filtros['ano'], fn (Builder $q, int $ano) => $q->whereYear('data_assinatura', $ano))
            ->when($filtros['tipo'], fn (Builder $q, string $tipo) => $q->where('tipo', $tipo))
            ->when($filtros['situacao'], fn (Builder $q, string $situacao) => $q->where('situacao', $situacao))
            ->when($filtros['busca'], fn (Builder $q, string $busca) => $q->where(fn (Builder $q) => $q
                ->where('numero', 'like', '%'.$busca.'%')
                ->orWhere('objeto', 'like', '%'.$busca.'%')
                ->orWhere('orgao_concedente', 'like', '%'.$busca.'%')))
            ->orderByDesc('data_assinatura')
            ->orderByDesc('id');
    }
}
