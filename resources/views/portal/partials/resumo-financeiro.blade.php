@use('App\Support\Formatar')
<section class="mb-6 grid gap-4 lg:grid-cols-3" aria-label="Resumo">
    <div class="cartao">
        <p class="text-sm text-texto-suave">{{ $rotuloTotal }} (filtros aplicados)</p>
        <p class="mt-1 text-3xl font-bold text-primaria-escura">{{ Formatar::moeda($total) }}</p>
    </div>
    <div class="cartao lg:col-span-2">
        <h2 class="text-sm font-semibold mb-3">{{ $tituloGrafico }}</h2>
        @if ($grupos->isEmpty())
            <p class="text-sm text-texto-suave">Sem dados para exibir.</p>
        @else
            {{-- Gráfico de barras em HTML/CSS: acessível, leve e funciona sem JavaScript. --}}
            @php $maior = max($grupos->max('total'), 0.01); @endphp
            <ul class="space-y-2">
                @foreach ($grupos as $grupo)
                    <li class="grid grid-cols-[1fr_auto] sm:grid-cols-[minmax(0,14rem)_1fr_auto] items-center gap-x-3 gap-y-1 text-sm">
                        <span class="truncate" title="{{ $grupo['rotulo'] }}">{{ $grupo['rotulo'] }}</span>
                        <span class="h-3 rounded bg-borda col-span-2 order-last sm:col-span-1 sm:order-none" aria-hidden="true"><span class="block h-3 rounded bg-primaria" style="width: {{ round($grupo['total'] / $maior * 100, 1) }}%"></span></span>
                        <span class="whitespace-nowrap font-medium">{{ Formatar::moeda($grupo['total']) }} <span class="text-texto-suave font-normal">({{ $total > 0 ? number_format($grupo['total'] / $total * 100, 1, ',', '.') : 0 }}%)</span></span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
