@props(['titulo', 'atualizadoEm' => null])
<div class="mb-6">
    <nav aria-label="Você está em" class="text-sm text-texto-suave mb-2">
        <a href="{{ route('inicio') }}" class="link">Início</a> <span aria-hidden="true">›</span> {{ $titulo }}
    </nav>
    <div class="flex flex-wrap items-end justify-between gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-primaria-escura">{{ $titulo }}</h1>
        @if ($atualizadoEm)
            <p class="text-sm text-texto-suave">
                Atualizado em <time datetime="{{ \Illuminate\Support\Carbon::parse($atualizadoEm)->toIso8601String() }}">{{ \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y \à\s H:i') }}</time>
            </p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="mt-2 max-w-3xl text-texto-suave">{{ $slot }}</div>
    @endif
</div>
