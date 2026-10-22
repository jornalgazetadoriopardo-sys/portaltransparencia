<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Em construção — Portal da Transparência — {{ $entidade->sigla ?: $entidade->nome }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex flex-col bg-fundo text-texto antialiased">
    <header class="bg-primaria text-white">
        <div class="max-w-3xl mx-auto px-4 py-5 flex items-center gap-3">
            @if ($entidade->logo)
                <img src="{{ Storage::disk('public')->url($entidade->logo) }}" alt="" class="h-12 w-12 rounded bg-white object-contain p-1">
            @endif
            <div>
                <p class="text-xs uppercase tracking-wider text-white/80">Portal da Transparência</p>
                <p class="text-lg sm:text-xl font-semibold leading-tight">{{ $entidade->nome }}</p>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center">
        <div class="max-w-3xl mx-auto px-4 py-16 text-center">
            <svg class="mx-auto h-20 w-20 text-primaria" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
            </svg>
            <h1 class="mt-6 text-3xl sm:text-4xl font-bold text-primaria-escura">Em construção</h1>
            <p class="mt-4 text-lg text-texto-suave">
                Estamos preparando o nosso Portal da Transparência, com informações sobre parcerias,
                receitas, despesas e prestação de contas. Em breve, tudo estará disponível aqui.
            </p>

            @if ($entidade->email || $entidade->telefone)
                <div class="cartao mt-10 inline-block text-left">
                    <p class="font-semibold">Precisa de alguma informação agora?</p>
                    <ul class="mt-2 space-y-1 text-sm">
                        @if ($entidade->email)
                            <li>E-mail: <a class="link" href="mailto:{{ $entidade->email }}">{{ $entidade->email }}</a></li>
                        @endif
                        @if ($entidade->telefone)
                            <li>Telefone: {{ $entidade->telefone }}</li>
                        @endif
                        @if ($entidade->horario_atendimento)
                            <li>{{ $entidade->horario_atendimento }}</li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </main>

    <footer class="border-t border-borda bg-white py-4 text-center text-xs text-texto-suave">
        <a href="{{ url('/admin') }}" class="hover:underline">Área restrita</a>
    </footer>
</body>
</html>
