@use('App\Support\Formatar')
<x-portal.layout titulo="Receitas">
    <x-portal.cabecalho titulo="Receitas" :atualizado-em="$atualizadoEm">
        Todos os recursos recebidos pela associação: repasses de parcerias, doações, contribuições e outras fontes.
    </x-portal.cabecalho>

    <x-portal.filtros :acao="route('receitas.index')">
        <x-portal.campo-select nome="ano" rotulo="Ano" :opcoes="array_combine($anos, $anos)" :valor="$filtros['ano']" />
        <x-portal.campo-select nome="fonte" rotulo="Fonte" :opcoes="$fontes" :valor="$filtros['fonte']" vazio="Todas" />
        <x-portal.campo-select nome="parceria" rotulo="Parceria" :opcoes="$parcerias->mapWithKeys(fn ($p) => [$p->id => $p->identificacao])" :valor="$filtros['parceria']" vazio="Todas" />
        <div>
            <label for="filtro-busca" class="rotulo">Palavra-chave</label>
            <input id="filtro-busca" name="busca" type="search" value="{{ $filtros['busca'] }}" class="campo" placeholder="Descrição...">
        </div>
    </x-portal.filtros>

    @include('portal.partials.resumo-financeiro', ['total' => $total, 'rotuloTotal' => 'Total recebido', 'grupos' => $porFonte, 'tituloGrafico' => 'Receitas por fonte'])

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm" role="status"><strong>{{ $receitas->total() }}</strong> lançamento(s)</p>
        <x-portal.exportar rota="receitas.exportar" />
    </div>

    @if ($receitas->isEmpty())
        <x-portal.vazio />
    @else
        <div class="overflow-x-auto">
            <table class="tabela">
                <caption class="sr-only">Lista de receitas</caption>
                <thead><tr><th scope="col">Data</th><th scope="col">Fonte</th><th scope="col">Descrição</th><th scope="col">Parceria</th><th scope="col" class="text-right">Valor</th></tr></thead>
                <tbody>
                    @foreach ($receitas as $receita)
                        <tr>
                            <td data-label="Data" class="whitespace-nowrap">{{ Formatar::data($receita->data) }}</td>
                            <td data-label="Fonte">{{ $receita->fonte->getLabel() }}</td>
                            <td data-label="Descrição">{{ $receita->descricao }}</td>
                            <td data-label="Parceria">
                                @if ($receita->parceria)<a class="link" href="{{ route('parcerias.show', $receita->parceria) }}">{{ $receita->parceria->numero }}</a>@else — @endif
                            </td>
                            <td data-label="Valor" class="text-right whitespace-nowrap font-medium">{{ Formatar::moeda($receita->valor) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $receitas->links() }}</div>
    @endif
</x-portal.layout>
