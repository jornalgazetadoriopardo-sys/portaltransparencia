@use('App\Support\Formatar')
<x-portal.layout titulo="Compras e contratações">
    <x-portal.cabecalho titulo="Compras e contratações" :atualizado-em="$atualizadoEm">
        Processos de cotação e seleção de fornecedores, conforme o regulamento de compras da associação.
    </x-portal.cabecalho>

    <x-portal.filtros :acao="route('compras.index')">
        <x-portal.campo-select nome="ano" rotulo="Ano" :opcoes="array_combine($anos, $anos)" :valor="$filtros['ano']" />
        <x-portal.campo-select nome="modalidade" rotulo="Modalidade" :opcoes="$modalidades" :valor="$filtros['modalidade']" vazio="Todas" />
        <x-portal.campo-select nome="situacao" rotulo="Situação" :opcoes="$situacoes" :valor="$filtros['situacao']" vazio="Todas" />
        <div>
            <label for="filtro-busca" class="rotulo">Palavra-chave</label>
            <input id="filtro-busca" name="busca" type="search" value="{{ $filtros['busca'] }}" class="campo" placeholder="Número, objeto, fornecedor...">
        </div>
    </x-portal.filtros>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm" role="status"><strong>{{ $compras->total() }}</strong> processo(s)</p>
        <x-portal.exportar rota="compras.exportar" />
    </div>

    @if ($compras->isEmpty())
        <x-portal.vazio />
    @else
        <div class="overflow-x-auto">
            <table class="tabela">
                <caption class="sr-only">Lista de compras e contratações</caption>
                <thead><tr><th scope="col">Número</th><th scope="col">Abertura</th><th scope="col">Modalidade</th><th scope="col">Objeto</th><th scope="col">Fornecedor</th><th scope="col">Situação</th><th scope="col" class="text-right">Valor</th><th scope="col"><span class="sr-only">Arquivo</span></th></tr></thead>
                <tbody>
                    @foreach ($compras as $compra)
                        <tr>
                            <td data-label="Número" class="font-medium whitespace-nowrap">{{ $compra->numero }}</td>
                            <td data-label="Abertura" class="whitespace-nowrap">{{ Formatar::data($compra->data_abertura) }}</td>
                            <td data-label="Modalidade">{{ $compra->modalidade->getLabel() }}</td>
                            <td data-label="Objeto">
                                {{ $compra->objeto }}
                                @if ($compra->parceria)<span class="block text-xs text-texto-suave">Parceria <a class="link" href="{{ route('parcerias.show', $compra->parceria) }}">{{ $compra->parceria->numero }}</a></span>@endif
                            </td>
                            <td data-label="Fornecedor">
                                {{ $compra->fornecedor ?? '—' }}
                                @if ($compra->fornecedor_documento_publico)<span class="block text-xs text-texto-suave">{{ $compra->fornecedor_documento_publico }}</span>@endif
                            </td>
                            <td data-label="Situação">{{ $compra->situacao->getLabel() }}</td>
                            <td data-label="Valor" class="text-right whitespace-nowrap">{{ $compra->valor !== null ? Formatar::moeda($compra->valor) : '—' }}</td>
                            <td data-label="Arquivo">
                                @if ($compra->arquivo)<a class="link" href="{{ Storage::disk('public')->url($compra->arquivo) }}" target="_blank" rel="noopener">PDF<span class="sr-only"> do processo {{ $compra->numero }}</span></a>@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $compras->links() }}</div>
    @endif
</x-portal.layout>
