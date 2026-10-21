@props(['acao'])
<form action="{{ $acao }}" method="get" class="mb-6 rounded-lg border border-borda bg-white p-4" aria-label="Filtros">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{ $slot }}
    </div>
    <div class="mt-4 flex flex-wrap gap-2">
        <button class="botao">Filtrar</button>
        <a href="{{ $acao }}" class="botao-secundario">Limpar filtros</a>
    </div>
</form>
