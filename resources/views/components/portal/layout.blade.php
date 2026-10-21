@props(['titulo' => null, 'descricao' => null])
@php
    $nomeEntidade = $entidade->sigla ?: $entidade->nome;
    $menu = [
        ['inicio', 'Início'],
        ['institucional', 'Institucional'],
        ['parcerias.index', 'Parcerias'],
        ['receitas.index', 'Receitas'],
        ['despesas.index', 'Despesas'],
        ['compras.index', 'Compras'],
        ['prestacao-contas', 'Prestação de contas'],
        ['documentos.index', 'Documentos'],
        ['pedido.create', 'Pedido de informação'],
    ];
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ? $titulo.' — ' : '' }}Portal da Transparência — {{ $nomeEntidade }}</title>
    <meta name="description" content="{{ $descricao ?? 'Portal da Transparência de '.$entidade->nome.': parcerias, receitas, despesas, compras e prestação de contas.' }}">
    <script>
        // Aplica preferências de acessibilidade antes de desenhar a página (evita "piscar").
        try {
            if (localStorage.getItem('pt-contraste') === '1') document.documentElement.classList.add('alto-contraste');
            const fonte = localStorage.getItem('pt-fonte');
            if (fonte) document.documentElement.style.fontSize = fonte + '%';
        } catch (e) {}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-fundo text-texto antialiased">
    <a href="#conteudo" accesskey="1" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:bg-primaria focus:text-white focus:px-4 focus:py-2 focus:rounded">Ir para o conteúdo</a>

    {{-- Barra de acessibilidade --}}
    <div class="bg-primaria-escura text-white text-sm">
        <div class="max-w-6xl mx-auto px-4 py-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 justify-between">
            <nav aria-label="Atalhos de acessibilidade" class="flex flex-wrap gap-x-4 gap-y-1">
                <a href="#conteudo" class="hover:underline">Conteúdo <kbd class="opacity-75">[1]</kbd></a>
                <a href="#menu" accesskey="2" class="hover:underline">Menu <kbd class="opacity-75">[2]</kbd></a>
                <a href="#busca" accesskey="3" class="hover:underline">Busca <kbd class="opacity-75">[3]</kbd></a>
                <a href="{{ route('acessibilidade') }}" class="hover:underline">Acessibilidade</a>
            </nav>
            <div class="flex items-center gap-1" role="group" aria-label="Ajustes de leitura">
                <button type="button" data-fonte="-10" class="px-2 py-0.5 rounded hover:bg-white/15" aria-label="Diminuir o tamanho do texto">A-</button>
                <button type="button" data-fonte="0" class="px-2 py-0.5 rounded hover:bg-white/15" aria-label="Tamanho normal do texto">A</button>
                <button type="button" data-fonte="10" class="px-2 py-0.5 rounded hover:bg-white/15" aria-label="Aumentar o tamanho do texto">A+</button>
                <button type="button" data-contraste class="px-2 py-0.5 rounded hover:bg-white/15" aria-pressed="false">Alto contraste</button>
            </div>
        </div>
    </div>

    <header class="bg-primaria text-white">
        <div class="max-w-6xl mx-auto px-4 py-5 flex flex-wrap items-center gap-4 justify-between">
            <a href="{{ route('inicio') }}" class="flex items-center gap-3 min-w-0">
                @if ($entidade->logo)
                    <img src="{{ Storage::disk('public')->url($entidade->logo) }}" alt="" class="h-12 w-12 rounded bg-white object-contain p-1">
                @endif
                <span class="min-w-0">
                    <span class="block text-xs uppercase tracking-wider text-white/80">Portal da Transparência</span>
                    <span class="block text-lg sm:text-xl font-semibold leading-tight">{{ $entidade->nome }}</span>
                </span>
            </a>
            <form action="{{ route('busca') }}" method="get" role="search" class="w-full sm:w-auto flex">
                <label for="busca" class="sr-only">Buscar no portal</label>
                <input id="busca" name="q" type="search" value="{{ request()->routeIs('busca') ? request('q') : '' }}" placeholder="Buscar parcerias, documentos..." class="w-full sm:w-72 rounded-l-md border-0 px-3 py-2 text-texto bg-white placeholder:text-gray-500 focus:ring-2 focus:ring-destaque">
                <button class="rounded-r-md bg-destaque px-4 font-medium text-primaria-escura hover:brightness-95">Buscar</button>
            </form>
        </div>
        <nav id="menu" aria-label="Menu principal" class="border-t border-white/15">
            <div class="max-w-6xl mx-auto px-4">
                <button type="button" class="md:hidden w-full py-3 text-left font-medium flex items-center justify-between" data-menu-botao aria-expanded="false" aria-controls="menu-lista">
                    Menu <span aria-hidden="true">☰</span>
                </button>
                <ul id="menu-lista" class="hidden md:flex flex-col md:flex-row md:flex-wrap pb-3 md:pb-0">
                    @foreach ($menu as [$rota, $rotulo])
                        @php $ativo = request()->routeIs($rota) || request()->routeIs(str($rota)->before('.').'.*'); @endphp
                        <li>
                            <a href="{{ route($rota) }}" @if ($ativo) aria-current="page" @endif
                               class="block px-3 py-2.5 text-sm font-medium hover:bg-white/10 {{ $ativo ? 'bg-white/15 md:shadow-[inset_0_-3px_0] md:shadow-destaque' : '' }}">
                                {{ $rotulo }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>

    <main id="conteudo" tabindex="-1" class="flex-1 w-full max-w-6xl mx-auto px-4 py-8 focus:outline-none">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-borda text-sm">
        <div class="max-w-6xl mx-auto px-4 py-8 grid gap-6 sm:grid-cols-3">
            <div>
                <p class="font-semibold">{{ $entidade->nome }}</p>
                @if ($entidade->cnpj)<p>CNPJ {{ $entidade->cnpj }}</p>@endif
                @if ($entidade->endereco)<p>{{ $entidade->endereco }}@if ($entidade->cidade_uf) — {{ $entidade->cidade_uf }}@endif</p>@endif
            </div>
            <div>
                <p class="font-semibold">Atendimento</p>
                @if ($entidade->telefone)<p>{{ $entidade->telefone }}</p>@endif
                @if ($entidade->email)<p><a class="link" href="mailto:{{ $entidade->email }}">{{ $entidade->email }}</a></p>@endif
                @if ($entidade->horario_atendimento)<p>{{ $entidade->horario_atendimento }}</p>@endif
            </div>
            <div>
                <p class="font-semibold">Mais informações</p>
                <ul class="space-y-0.5">
                    <li><a class="link" href="{{ route('perguntas') }}">Perguntas frequentes</a></li>
                    <li><a class="link" href="{{ route('dados-abertos') }}">Dados abertos</a></li>
                    <li><a class="link" href="{{ route('privacidade') }}">Privacidade e LGPD</a></li>
                    <li><a class="link" href="{{ route('acessibilidade') }}">Acessibilidade</a></li>
                </ul>
            </div>
        </div>
        <p class="text-center text-xs text-texto-suave pb-6 px-4">
            Informações publicadas em cumprimento à Lei nº 12.527/2011 (Lei de Acesso à Informação) e à Lei nº 13.019/2014 (Marco Regulatório das Organizações da Sociedade Civil).
        </p>
    </footer>

    @if (config('portal.vlibras'))
        <div vw class="enabled">
            <div vw-access-button class="active"></div>
            <div vw-plugin-wrapper><div class="vw-plugin-top-wrapper"></div></div>
        </div>
        <script src="https://vlibras.gov.br/app/vlibras-plugin.js" defer></script>
        <script>window.addEventListener('load', () => { if (window.VLibras) new window.VLibras.Widget('https://vlibras.gov.br/app'); });</script>
    @endif
</body>
</html>
