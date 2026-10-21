<x-portal.layout titulo="Pedido de informação">
    <x-portal.cabecalho titulo="Pedido de informação">
        Não encontrou o que procurava? Envie seu pedido. Você recebe um número de protocolo e pode acompanhar a resposta,
        que será dada em até {{ config('portal.prazo_resposta_dias') }} dias. Não é preciso justificar o motivo do pedido.
    </x-portal.cabecalho>

    @if (session('protocolo'))
        <div class="mb-6 rounded-lg border border-green-700 bg-green-50 p-5 text-green-900" role="alert">
            <p class="font-semibold">Pedido registrado com sucesso!</p>
            <p class="mt-1">Seu protocolo é <strong class="text-lg tracking-wide">{{ session('protocolo') }}</strong>. Guarde este número para consultar a resposta.</p>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <form action="{{ route('pedido.store') }}" method="post" class="cartao lg:col-span-2 space-y-4" novalidate>
            @csrf
            @if ($errors->any())
                <div class="rounded border border-red-700 bg-red-50 p-4 text-red-900" role="alert">
                    <p class="font-semibold">Corrija os campos indicados:</p>
                    <ul class="list-disc pl-5 text-sm">@foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="hidden" aria-hidden="true"><label>Não preencha <input type="text" name="site" tabindex="-1" autocomplete="off"></label></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="nome" class="rotulo">Nome <span aria-hidden="true">*</span></label>
                    <input id="nome" name="nome" value="{{ old('nome') }}" required autocomplete="name" class="campo" @error('nome') aria-invalid="true" @enderror>
                </div>
                <div>
                    <label for="email" class="rotulo">E-mail <span aria-hidden="true">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="campo" @error('email') aria-invalid="true" @enderror>
                </div>
                <div>
                    <label for="telefone" class="rotulo">Telefone (opcional)</label>
                    <input id="telefone" name="telefone" type="tel" value="{{ old('telefone') }}" autocomplete="tel" class="campo">
                </div>
                <div>
                    <label for="assunto" class="rotulo">Assunto <span aria-hidden="true">*</span></label>
                    <input id="assunto" name="assunto" value="{{ old('assunto') }}" required class="campo" @error('assunto') aria-invalid="true" @enderror>
                </div>
            </div>
            <div>
                <label for="mensagem" class="rotulo">Descreva a informação que deseja <span aria-hidden="true">*</span></label>
                <textarea id="mensagem" name="mensagem" rows="6" required maxlength="5000" class="campo" @error('mensagem') aria-invalid="true" @enderror>{{ old('mensagem') }}</textarea>
            </div>
            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="aceite" value="1" @checked(old('aceite')) class="mt-1">
                <span>Estou ciente de que meus dados serão usados apenas para responder a este pedido, conforme a <a class="link" href="{{ route('privacidade') }}">política de privacidade</a>.</span>
            </label>
            <button class="botao">Enviar pedido</button>
        </form>

        <aside class="space-y-4">
            <div class="cartao">
                <h2 class="font-semibold mb-2">Já fez um pedido?</h2>
                <p class="text-sm text-texto-suave mb-3">Consulte o andamento com o protocolo e o e-mail informado.</p>
                <a href="{{ route('pedido.consultar') }}" class="botao-secundario">Consultar pedido</a>
            </div>
            <div class="cartao text-sm">
                <h2 class="font-semibold mb-2">Atendimento presencial</h2>
                @if ($entidade->endereco)<p>{{ $entidade->endereco }} — {{ $entidade->cidade_uf }}</p>@endif
                @if ($entidade->horario_atendimento)<p>{{ $entidade->horario_atendimento }}</p>@endif
                @if ($entidade->telefone)<p>{{ $entidade->telefone }}</p>@endif
            </div>
        </aside>
    </div>
</x-portal.layout>
