@use('App\Support\Formatar')
<x-portal.layout titulo="Despesas">
    <x-portal.cabecalho titulo="Despesas" :atualizado-em="$atualizadoEm">
        Pagamentos realizados pela associação, com favorecido, documento fiscal e a parceria que custeou a despesa.
    </x-portal.cabecalho>

    <x-portal.filtros :acao="route('despesas.index')">
        <x-portal.campo-select nome="ano" rotulo="Ano" :opcoes="array_combine($anos, $anos)" :valor="$filtros['ano']" />
        <x-portal.campo-select nome="categoria" rotulo="Categoria" :opcoes="$categorias" :valor="$filtros['categoria']" vazio="Todas" />
        <x-portal.campo-select nome="parceria" rotulo="Parceria" :opcoes="$parcerias->mapWithKeys(fn ($p) => [$p->id => $p->identificacao])" :valor="$filtros['parceria']" vazio="Todas" />
        <div>
            <label for="filtro-busca" class="rotulo">Palavra-chave</label>
            <input id="filtro-busca" name="busca" type="search" value="{{ $filtros['busca'] }}" class="campo" placeholder="Favorecido, descrição, nota...">
        </div>
    </x-portal.filtros>

    @include('portal.partials.resumo-financeiro', ['total' => $total, 'rotuloTotal' => 'Total pago', 'grupos' => $porCategoria, 'tituloGrafico' => 'Despesas por categoria'])

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm" role="status"><strong>{{ $despesas->total() }}</strong> lançamento(s)</p>
        <x-portal.exportar rota="despesas.exportar" />
    </div>

    @if ($despesas->isEmpty())
        <x-portal.vazio />
    @else
        <div class="overflow-x-auto">
            <table class="tabela">
                <caption class="sr-only">Lista de despesas</caption>
                <thead><tr><th scope="col">Data</th><th scope="col">Categoria</th><th scope="col">Favorecido</th><th scope="col">Descrição</th><th scope="col">Parceria</th><th scope="col" class="text-right">Valor</th></tr></thead>
                <tbody>
                    @foreach ($despesas as $despesa)
                        <tr>
                            <td data-label="Data" class="whitespace-nowrap">{{ Formatar::data($despesa->data) }}</td>
                            <td data-label="Categoria">{{ $despesa->categoria->getLabel() }}</td>
                            <td data-label="Favorecido">
                                <span class="font-medium">{{ $despesa->favorecido }}</span>
                                @if ($despesa->favorecido_documento_publico)<span class="block text-xs text-texto-suave">{{ $despesa->favorecido_documento_publico }}</span>@endif
                            </td>
                            <td data-label="Descrição">
                                {{ $despesa->descricao }}
                                @if ($despesa->documento_fiscal)<span class="block text-xs text-texto-suave">Doc. {{ $despesa->documento_fiscal }}</span>@endif
                            </td>
                            <td data-label="Parceria">
                                @if ($despesa->parceria)<a class="link" href="{{ route('parcerias.show', $despesa->parceria) }}">{{ $despesa->parceria->numero }}</a>@else — @endif
                            </td>
                            <td data-label="Valor" class="text-right whitespace-nowrap font-medium">{{ Formatar::moeda($despesa->valor) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $despesas->links() }}</div>
    @endif
</x-portal.layout>
