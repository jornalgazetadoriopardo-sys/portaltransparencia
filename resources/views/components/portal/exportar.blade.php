@props(['rota'])
<div class="flex flex-wrap items-center gap-2 text-sm" aria-label="Baixar os dados desta consulta">
    <span class="text-texto-suave">Baixar dados:</span>
    <a href="{{ route($rota, request()->except('page')) }}" class="botao-secundario">CSV (Excel)</a>
    <a href="{{ route($rota, array_merge(request()->except('page'), ['formato' => 'json'])) }}" class="botao-secundario">JSON</a>
    <button type="button" onclick="window.print()" class="botao-secundario">Imprimir / PDF</button>
</div>
