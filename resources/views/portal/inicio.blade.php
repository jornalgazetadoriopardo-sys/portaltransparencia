@use('App\Support\Formatar')
<x-portal.layout>
    <section class="mb-10" aria-labelledby="titulo-inicio">
        <h1 id="titulo-inicio" class="text-2xl sm:text-3xl font-bold text-primaria-escura">Transparência é compromisso</h1>
        <p class="mt-2 max-w-3xl text-texto-suave">
            Aqui você acompanha de onde vêm e como são usados os recursos da {{ $entidade->sigla ?: $entidade->nome }}:
            parcerias com o poder público, receitas, despesas, compras e prestações de contas.
        </p>
    </section>

    <section aria-labelledby="titulo-numeros" class="mb-10">
        <h2 id="titulo-numeros" class="sr-only">Números de {{ $ano }}</h2>
        <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="cartao">
                <dt class="text-sm text-texto-suave">Receitas em {{ $ano }}</dt>
                <dd class="mt-1 text-2xl font-bold text-primaria-escura">{{ Formatar::moeda($receitasAno) }}</dd>
            </div>
            <div class="cartao">
                <dt class="text-sm text-texto-suave">Despesas em {{ $ano }}</dt>
                <dd class="mt-1 text-2xl font-bold text-primaria-escura">{{ Formatar::moeda($despesasAno) }}</dd>
            </div>
            <div class="cartao">
                <dt class="text-sm text-texto-suave">Parcerias em execução</dt>
                <dd class="mt-1 text-2xl font-bold text-primaria-escura">{{ $parceriasVigentes }}</dd>
            </div>
            <div class="cartao">
                <dt class="text-sm text-texto-suave">Valor das parcerias em execução</dt>
                <dd class="mt-1 text-2xl font-bold text-primaria-escura">{{ Formatar::moeda($recursosParcerias) }}</dd>
            </div>
        </dl>
    </section>

    @php
        $secoes = [
            ['institucional', 'Institucional', 'Estatuto, diretoria, conselhos e dados de contato.', $contagem['institucional'].' dirigentes'],
            ['parcerias.index', 'Parcerias e convênios', 'Termos de colaboração, fomento, convênios e sua execução.', $contagem['parcerias'].' parcerias'],
            ['receitas.index', 'Receitas', 'Todos os recursos recebidos, por fonte.', $contagem['receitas'].' lançamentos'],
            ['despesas.index', 'Despesas', 'Pagamentos realizados, por categoria e favorecido.', $contagem['despesas'].' lançamentos'],
            ['compras.index', 'Compras e contratações', 'Cotações, seleções de fornecedores e contratos.', $contagem['compras'].' processos'],
            ['prestacao-contas', 'Prestação de contas', 'Balanços, pareceres, relatórios e situação das prestações.', 'por ano'],
            ['documentos.index', 'Documentos', 'Atas, certidões, regulamentos e demais publicações.', $contagem['documentos'].' documentos'],
            ['pedido.create', 'Pedido de informação', 'Não encontrou o que procura? Faça seu pedido.', 'resposta em até '.config('portal.prazo_resposta_dias').' dias'],
        ];
    @endphp
    <section aria-labelledby="titulo-secoes" class="mb-10">
        <h2 id="titulo-secoes" class="text-xl font-semibold mb-4">O que você procura?</h2>
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($secoes as [$rota, $titulo, $texto, $info])
                <li>
                    <a href="{{ route($rota) }}" class="cartao block h-full hover:border-primaria hover:shadow-md transition group">
                        <span class="block font-semibold text-primaria-escura group-hover:underline">{{ $titulo }}</span>
                        <span class="block mt-1 text-sm text-texto-suave">{{ $texto }}</span>
                        <span class="block mt-3 text-xs font-medium text-primaria">{{ $info }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="grid gap-8 lg:grid-cols-2">
        <section aria-labelledby="titulo-parcerias">
            <h2 id="titulo-parcerias" class="text-xl font-semibold mb-4">Parcerias mais recentes</h2>
            @forelse ($ultimasParcerias as $parceria)
                <a href="{{ route('parcerias.show', $parceria) }}" class="cartao block mb-3 hover:border-primaria">
                    <span class="block text-sm text-texto-suave">{{ $parceria->identificacao }} · {{ $parceria->orgao_concedente }}</span>
                    <span class="block font-medium mt-1">{{ str($parceria->objeto)->limit(120) }}</span>
                    <span class="block mt-2 text-sm"><strong>{{ Formatar::moeda($parceria->valor_total) }}</strong> · {{ $parceria->situacao->getLabel() }}</span>
                </a>
            @empty
                <x-portal.vazio>Nenhuma parceria publicada até o momento.</x-portal.vazio>
            @endforelse
        </section>

        <section aria-labelledby="titulo-documentos">
            <h2 id="titulo-documentos" class="text-xl font-semibold mb-4">Últimas publicações</h2>
            @forelse ($ultimosDocumentos as $documento)
                <a href="{{ $documento->url }}" class="cartao flex items-start gap-3 mb-3 hover:border-primaria" target="_blank" rel="noopener">
                    <span class="selo">{{ $documento->extensao }}</span>
                    <span>
                        <span class="block font-medium">{{ $documento->titulo }}</span>
                        <span class="block text-sm text-texto-suave">{{ $documento->categoria->getLabel() }} · {{ Formatar::data($documento->data_publicacao) }}</span>
                    </span>
                </a>
            @empty
                <x-portal.vazio>Nenhum documento publicado até o momento.</x-portal.vazio>
            @endforelse
        </section>
    </div>
</x-portal.layout>
