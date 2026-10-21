@use('App\Support\Formatar')
@if ($documentos->isEmpty())
    <x-portal.vazio>Nenhum documento publicado nesta seção.</x-portal.vazio>
@else
    <ul class="divide-y divide-borda rounded-lg border border-borda bg-white">
        @foreach ($documentos as $documento)
            <li class="flex flex-wrap items-center gap-3 p-4">
                <span class="selo">{{ $documento->extensao }}</span>
                <div class="flex-1 min-w-48">
                    <a href="{{ $documento->url }}" class="font-medium link" target="_blank" rel="noopener">{{ $documento->titulo }}<span class="sr-only"> ({{ $documento->extensao }}, abre em nova aba)</span></a>
                    <p class="text-sm text-texto-suave">
                        {{ $documento->categoria->getLabel() }}@if ($documento->ano) · referência {{ $documento->ano }}@endif · publicado em {{ Formatar::data($documento->data_publicacao) }}
                    </p>
                    @if ($documento->descricao)<p class="text-sm mt-1">{{ $documento->descricao }}</p>@endif
                </div>
                <a href="{{ $documento->url }}" download class="botao-secundario text-sm">Baixar</a>
            </li>
        @endforeach
    </ul>
@endif
