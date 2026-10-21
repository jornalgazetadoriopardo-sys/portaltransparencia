<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Documento;
use App\Models\Parceria;
use App\Models\PerguntaFrequente;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BuscaController extends Controller
{
    public function __invoke(Request $request): View
    {
        $termo = trim((string) $request->validate(['q' => ['nullable', 'string', 'max:100']])['q'] ?? '');
        $resultados = collect();

        if (mb_strlen($termo) >= 3) {
            $like = '%'.$termo.'%';

            $resultados = collect()
                ->merge(Parceria::query()->publicadas()
                    ->where(fn ($q) => $q->where('numero', 'like', $like)->orWhere('objeto', 'like', $like)->orWhere('orgao_concedente', 'like', $like))
                    ->limit(10)->get()
                    ->map(fn (Parceria $p) => [
                        'tipo' => 'Parceria',
                        'titulo' => $p->identificacao,
                        'resumo' => $p->objeto,
                        'url' => route('parcerias.show', $p),
                    ]))
                ->merge(Documento::query()->publicados()
                    ->where(fn ($q) => $q->where('titulo', 'like', $like)->orWhere('descricao', 'like', $like))
                    ->limit(10)->get()
                    ->map(fn (Documento $d) => [
                        'tipo' => 'Documento',
                        'titulo' => $d->titulo,
                        'resumo' => $d->categoria->getLabel().($d->ano ? ' — '.$d->ano : ''),
                        'url' => $d->url,
                    ]))
                ->merge(Compra::query()
                    ->where(fn ($q) => $q->where('numero', 'like', $like)->orWhere('objeto', 'like', $like)->orWhere('fornecedor', 'like', $like))
                    ->limit(10)->get()
                    ->map(fn (Compra $c) => [
                        'tipo' => 'Compra / contratação',
                        'titulo' => $c->numero,
                        'resumo' => $c->objeto,
                        'url' => route('compras.index', ['busca' => $c->numero]),
                    ]))
                ->merge(PerguntaFrequente::query()->where('publicado', true)
                    ->where(fn ($q) => $q->where('pergunta', 'like', $like)->orWhere('resposta', 'like', $like))
                    ->limit(5)->get()
                    ->map(fn (PerguntaFrequente $p) => [
                        'tipo' => 'Pergunta frequente',
                        'titulo' => $p->pergunta,
                        'resumo' => $p->resposta,
                        'url' => route('perguntas').'#pergunta-'.$p->id,
                    ]));
        }

        return view('portal.busca', [
            'termo' => $termo,
            'resultados' => $resultados,
        ]);
    }
}
