<x-portal.layout titulo="Consultar pedido de informação">
    <x-portal.cabecalho titulo="Consultar pedido de informação" />

    <form action="{{ route('pedido.consultar') }}" method="get" class="cartao mb-6 grid gap-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
        <div>
            <label for="protocolo" class="rotulo">Protocolo</label>
            <input id="protocolo" name="protocolo" value="{{ request('protocolo') }}" required class="campo" placeholder="2026-ABCD1234">
        </div>
        <div>
            <label for="email" class="rotulo">E-mail usado no pedido</label>
            <input id="email" name="email" type="email" value="{{ request('email') }}" required class="campo">
        </div>
        <button class="botao">Consultar</button>
    </form>

    @if ($consultou && ! $pedido)
        <div class="rounded-lg border border-amber-700 bg-amber-50 p-4 text-amber-900" role="alert">Nenhum pedido encontrado com esse protocolo e e-mail.</div>
    @elseif ($pedido)
        <article class="cartao" aria-live="polite">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h2 class="text-lg font-semibold">Protocolo {{ $pedido->protocolo }}</h2>
                <span class="selo-status">{{ $pedido->status->getLabel() }}</span>
            </div>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-texto-suave">Recebido em</dt><dd>{{ $pedido->created_at->format('d/m/Y H:i') }}</dd></div>
                <div><dt class="text-texto-suave">Assunto</dt><dd class="font-medium">{{ $pedido->assunto }}</dd></div>
                <div><dt class="text-texto-suave">Pedido</dt><dd class="whitespace-pre-line">{{ $pedido->mensagem }}</dd></div>
                @if ($pedido->resposta)
                    <div class="rounded border border-borda bg-fundo p-4">
                        <dt class="font-semibold">Resposta @if ($pedido->respondido_em)<span class="font-normal text-texto-suave">— {{ $pedido->respondido_em->format('d/m/Y') }}</span>@endif</dt>
                        <dd class="mt-1 whitespace-pre-line">{{ $pedido->resposta }}</dd>
                    </div>
                @else
                    <div><dt class="text-texto-suave">Resposta</dt><dd>Prazo para resposta: até {{ $pedido->created_at->addDays(config('portal.prazo_resposta_dias'))->format('d/m/Y') }}.</dd></div>
                @endif
            </dl>
        </article>
    @endif
</x-portal.layout>
