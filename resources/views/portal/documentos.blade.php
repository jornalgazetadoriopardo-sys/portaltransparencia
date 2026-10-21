<x-portal.layout titulo="Documentos">
    <x-portal.cabecalho titulo="Documentos" :atualizado-em="$atualizadoEm">
        Todos os documentos publicados pela associação.
    </x-portal.cabecalho>

    <x-portal.filtros :acao="route('documentos.index')">
        <x-portal.campo-select nome="categoria" rotulo="Categoria" :opcoes="$categorias" :valor="$filtros['categoria']" vazio="Todas" />
        <x-portal.campo-select nome="ano" rotulo="Ano de referência" :opcoes="$anos->combine($anos)" :valor="$filtros['ano']" />
        <div class="lg:col-span-2">
            <label for="filtro-busca" class="rotulo">Palavra-chave</label>
            <input id="filtro-busca" name="busca" type="search" value="{{ $filtros['busca'] }}" class="campo" placeholder="Título ou descrição...">
        </div>
    </x-portal.filtros>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm" role="status"><strong>{{ $documentos->total() }}</strong> documento(s)</p>
        <x-portal.exportar rota="documentos.exportar" />
    </div>

    @include('portal.partials.lista-documentos', ['documentos' => $documentos])
    <div class="mt-4">{{ $documentos->links() }}</div>
</x-portal.layout>
