<div class="rounded-lg border border-dashed border-borda bg-white p-8 text-center text-texto-suave">
    {{ $slot->isNotEmpty() ? $slot : 'Nenhum registro encontrado para os filtros escolhidos.' }}
</div>
