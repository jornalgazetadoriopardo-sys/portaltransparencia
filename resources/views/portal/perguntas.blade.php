<x-portal.layout titulo="Perguntas frequentes">
    <x-portal.cabecalho titulo="Perguntas frequentes" />

    @forelse ($perguntas as $pergunta)
        <details id="pergunta-{{ $pergunta->id }}" class="mb-3 rounded-lg border border-borda bg-white">
            <summary class="cursor-pointer select-none p-4 font-semibold">{{ $pergunta->pergunta }}</summary>
            <div class="px-4 pb-4 whitespace-pre-line">{{ $pergunta->resposta }}</div>
        </details>
    @empty
        <x-portal.vazio>Nenhuma pergunta cadastrada ainda. <a class="link" href="{{ route('pedido.create') }}">Faça um pedido de informação</a>.</x-portal.vazio>
    @endforelse
</x-portal.layout>
