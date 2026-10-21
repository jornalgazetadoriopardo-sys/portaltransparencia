@use('App\Support\Formatar')
<x-portal.layout :titulo="$parceria->identificacao">
    <nav aria-label="Você está em" class="text-sm text-texto-suave mb-2">
        <a href="{{ route('inicio') }}" class="link">Início</a> <span aria-hidden="true">›</span>
        <a href="{{ route('parcerias.index') }}" class="link">Parcerias</a> <span aria-hidden="true">›</span> Nº {{ $parceria->numero }}
    </nav>
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primaria-escura">{{ $parceria->identificacao }}</h1>
            <p class="text-texto-suave mt-1">{{ $parceria->orgao_concedente }}</p>
        </div>
        <span class="selo-status">{{ $parceria->situacao->getLabel() }}</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="cartao lg:col-span-2" aria-labelledby="titulo-instrumento">
            <h2 id="titulo-instrumento" class="text-lg font-semibold mb-4">Dados da parceria</h2>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-texto-suave">Instrumento</dt><dd class="font-medium">{{ $parceria->identificacao }}</dd></div>
                <div><dt class="text-texto-suave">Data de assinatura</dt><dd class="font-medium">{{ Formatar::data($parceria->data_assinatura) }}</dd></div>
                <div><dt class="text-texto-suave">Órgão concedente</dt><dd class="font-medium">{{ $parceria->orgao_concedente }}</dd></div>
                <div><dt class="text-texto-suave">Organização</dt><dd class="font-medium">{{ $entidade->nome }}@if ($entidade->cnpj) — CNPJ {{ $entidade->cnpj }}@endif</dd></div>
                <div class="sm:col-span-2"><dt class="text-texto-suave">Objeto</dt><dd class="whitespace-pre-line">{{ $parceria->objeto }}</dd></div>
                <div><dt class="text-texto-suave">Vigência</dt><dd>{{ Formatar::data($parceria->vigencia_inicio) }} a {{ Formatar::data($parceria->vigencia_fim) }}</dd></div>
            </dl>
        </section>

        <section class="cartao" aria-labelledby="titulo-valores">
            <h2 id="titulo-valores" class="text-lg font-semibold mb-4">Valores</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-texto-suave">Valor total</dt><dd class="text-xl font-bold text-primaria-escura">{{ Formatar::moeda($parceria->valor_total) }}</dd></div>
                <div><dt class="text-texto-suave">Valor liberado</dt><dd class="font-semibold">{{ Formatar::moeda($parceria->valor_liberado) }}</dd></div>
                @if ($parceria->contrapartida > 0)<div><dt class="text-texto-suave">Contrapartida da entidade</dt><dd class="font-semibold">{{ Formatar::moeda($parceria->contrapartida) }}</dd></div>@endif
                <div><dt class="text-texto-suave">Receitas registradas</dt><dd><a class="link" href="{{ route('receitas.index', ['parceria' => $parceria->id]) }}">{{ Formatar::moeda($totalRecebido) }}</a></dd></div>
                <div><dt class="text-texto-suave">Despesas registradas</dt><dd><a class="link" href="{{ route('despesas.index', ['parceria' => $parceria->id]) }}">{{ Formatar::moeda($totalGasto) }}</a></dd></div>
            </dl>
        </section>
    </div>

    <section class="cartao mt-6" aria-labelledby="titulo-prestacao">
        <h2 id="titulo-prestacao" class="text-lg font-semibold mb-4">Situação da prestação de contas</h2>
        <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-4 text-sm">
            <div><dt class="text-texto-suave">Situação</dt><dd class="font-medium">{{ $parceria->prestacao_situacao->getLabel() }}</dd></div>
            <div><dt class="text-texto-suave">Data prevista</dt><dd>{{ Formatar::data($parceria->prestacao_data_prevista) }}</dd></div>
            <div><dt class="text-texto-suave">Apresentada em</dt><dd>{{ Formatar::data($parceria->prestacao_data_apresentacao) }}</dd></div>
            <div><dt class="text-texto-suave">Prazo de análise</dt><dd>{{ $parceria->prestacao_prazo_analise_dias ? $parceria->prestacao_prazo_analise_dias.' dias' : '—' }}</dd></div>
            @if ($parceria->prestacao_resultado)
                <div class="sm:col-span-4"><dt class="text-texto-suave">Resultado</dt><dd class="whitespace-pre-line">{{ $parceria->prestacao_resultado }}</dd></div>
            @endif
        </dl>
    </section>

    <section class="mt-6" aria-labelledby="titulo-equipe">
        <h2 id="titulo-equipe" class="text-lg font-semibold mb-3">Equipe remunerada com recursos da parceria</h2>
        @if ($parceria->equipe->isEmpty())
            <x-portal.vazio>Não há equipe remunerada com recursos desta parceria.</x-portal.vazio>
        @else
            <div class="overflow-x-auto">
                <table class="tabela">
                    <caption class="sr-only">Funções e remuneração da equipe</caption>
                    <thead><tr><th scope="col">Exercício</th><th scope="col">Função</th><th scope="col" class="text-right">Quantidade</th><th scope="col" class="text-right">Horas/semana</th><th scope="col" class="text-right">Remuneração mensal</th><th scope="col" class="text-right">Total mensal</th></tr></thead>
                    <tbody>
                        @foreach ($parceria->equipe as $membro)
                            <tr>
                                <td data-label="Exercício">{{ $membro->exercicio }}</td>
                                <td data-label="Função" class="font-medium">{{ $membro->funcao }}</td>
                                <td data-label="Quantidade" class="text-right">{{ $membro->quantidade }}</td>
                                <td data-label="Horas/semana" class="text-right">{{ $membro->carga_horaria_semanal ?? '—' }}</td>
                                <td data-label="Remuneração mensal" class="text-right">{{ Formatar::moeda($membro->remuneracao_mensal) }}</td>
                                <td data-label="Total mensal" class="text-right font-medium">{{ Formatar::moeda($membro->remuneracao_mensal * $membro->quantidade) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="mt-6" aria-labelledby="titulo-arquivos">
        <h2 id="titulo-arquivos" class="text-lg font-semibold mb-3">Documentos da parceria</h2>
        <div class="flex flex-wrap gap-3 mb-4">
            @if ($parceria->arquivo_instrumento)
                <a href="{{ Storage::disk('public')->url($parceria->arquivo_instrumento) }}" class="botao" target="_blank" rel="noopener">Íntegra do instrumento (PDF)</a>
            @endif
            @if ($parceria->arquivo_plano_trabalho)
                <a href="{{ Storage::disk('public')->url($parceria->arquivo_plano_trabalho) }}" class="botao" target="_blank" rel="noopener">Plano de trabalho (PDF)</a>
            @endif
        </div>
        @if ($parceria->documentos->isNotEmpty())
            @include('portal.partials.lista-documentos', ['documentos' => $parceria->documentos])
        @endif
    </section>
</x-portal.layout>
