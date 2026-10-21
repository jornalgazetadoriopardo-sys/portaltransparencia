<x-portal.layout titulo="Busca">
    <x-portal.cabecalho titulo="Busca" />

    <form action="{{ route('busca') }}" method="get" role="search" class="cartao mb-6 flex gap-2">
        <label for="busca-pagina" class="sr-only">Termo de busca</label>
        <input id="busca-pagina" name="q" type="search" value="{{ $termo }}" class="campo" placeholder="Digite pelo menos 3 letras" minlength="3">
        <button class="botao">Buscar</button>
    </form>

    @if (mb_strlen($termo) < 3)
        <p class="text-texto-suave">Digite pelo menos 3 letras para buscar em parcerias, documentos, compras e perguntas frequentes.</p>
    @elseif ($resultados->isEmpty())
        <x-portal.vazio>Nada encontrado para “{{ $termo }}”. Tente outra palavra ou <a class="link" href="{{ route('pedido.create') }}">faça um pedido de informação</a>.</x-portal.vazio>
    @else
        <p class="mb-4 text-sm" role="status">{{ $resultados->count() }} resultado(s) para “{{ $termo }}”</p>
        <ul class="space-y-3">
            @foreach ($resultados as $resultado)
                <li class="cartao">
                    <span class="text-xs font-medium uppercase tracking-wide text-primaria">{{ $resultado['tipo'] }}</span>
                    <a href="{{ $resultado['url'] }}" class="block font-semibold link mt-1">{{ $resultado['titulo'] }}</a>
                    <p class="text-sm text-texto-suave mt-1">{{ str($resultado['resumo'])->limit(200) }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</x-portal.layout>
