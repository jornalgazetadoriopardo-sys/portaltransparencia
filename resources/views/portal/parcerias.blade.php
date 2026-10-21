@use('App\Support\Formatar')
<x-portal.layout titulo="Parcerias e convênios">
    <x-portal.cabecalho titulo="Parcerias e convênios" :atualizado-em="$atualizadoEm">
        Parcerias firmadas com a administração pública, com as informações exigidas pelo art. 11 da Lei nº 13.019/2014.
    </x-portal.cabecalho>

    <x-portal.filtros :acao="route('parcerias.index')">
        <x-portal.campo-select nome="ano" rotulo="Ano de assinatura" :opcoes="array_combine($anos, $anos)" :valor="$filtros['ano']" />
        <x-portal.campo-select nome="tipo" rotulo="Tipo de instrumento" :opcoes="$tipos" :valor="$filtros['tipo']" />
        <x-portal.campo-select nome="situacao" rotulo="Situação" :opcoes="$situacoes" :valor="$filtros['situacao']" vazio="Todas" />
        <div>
            <label for="filtro-busca" class="rotulo">Palavra-chave</label>
            <input id="filtro-busca" name="busca" type="search" value="{{ $filtros['busca'] }}" class="campo" placeholder="Número, objeto, órgão...">
        </div>
    </x-portal.filtros>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm" role="status">
            <strong>{{ $parcerias->total() }}</strong> parceria(s) ·
            valor total <strong>{{ Formatar::moeda($totais['valor_total']) }}</strong> ·
            liberado <strong>{{ Formatar::moeda($totais['valor_liberado']) }}</strong>
        </p>
        <x-portal.exportar rota="parcerias.exportar" />
    </div>

    @forelse ($parcerias as $parceria)
        @php $percentual = $parceria->valor_total > 0 ? min(100, round($parceria->valor_liberado / $parceria->valor_total * 100)) : 0; @endphp
        <article class="cartao mb-4">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                    <p class="text-sm text-texto-suave">{{ $parceria->tipo->getLabel() }} · assinado em {{ Formatar::data($parceria->data_assinatura) }}</p>
                    <h2 class="text-lg font-semibold">
                        <a href="{{ route('parcerias.show', $parceria) }}" class="link">Nº {{ $parceria->numero }} — {{ $parceria->orgao_concedente }}</a>
                    </h2>
                </div>
                <span class="selo-status">{{ $parceria->situacao->getLabel() }}</span>
            </div>
            <p class="mt-2">{{ str($parceria->objeto)->limit(240) }}</p>
            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-4">
                <div><dt class="text-texto-suave">Valor total</dt><dd class="font-semibold">{{ Formatar::moeda($parceria->valor_total) }}</dd></div>
                <div><dt class="text-texto-suave">Valor liberado</dt><dd class="font-semibold">{{ Formatar::moeda($parceria->valor_liberado) }}</dd></div>
                <div><dt class="text-texto-suave">Vigência</dt><dd>{{ Formatar::data($parceria->vigencia_inicio) }} a {{ Formatar::data($parceria->vigencia_fim) }}</dd></div>
                <div><dt class="text-texto-suave">Prestação de contas</dt><dd>{{ $parceria->prestacao_situacao->getLabel() }}</dd></div>
            </dl>
            <div class="mt-3">
                <div class="h-2 rounded-full bg-borda" role="progressbar" aria-valuenow="{{ $percentual }}" aria-valuemin="0" aria-valuemax="100" aria-label="Percentual liberado">
                    <div class="h-2 rounded-full bg-primaria" style="width: {{ $percentual }}%"></div>
                </div>
                <p class="mt-1 text-xs text-texto-suave">{{ $percentual }}% do valor liberado</p>
            </div>
        </article>
    @empty
        <x-portal.vazio />
    @endforelse

    {{ $parcerias->links() }}
</x-portal.layout>
