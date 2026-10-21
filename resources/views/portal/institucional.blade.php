@use('App\Support\Formatar')
<x-portal.layout titulo="Institucional">
    <x-portal.cabecalho titulo="Institucional" :atualizado-em="$atualizadoEm" />

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="cartao lg:col-span-2" aria-labelledby="titulo-dados">
            <h2 id="titulo-dados" class="text-lg font-semibold mb-4">Dados da entidade</h2>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div class="sm:col-span-2"><dt class="text-texto-suave">Razão social</dt><dd class="font-medium">{{ $entidade->nome }}</dd></div>
                @if ($entidade->sigla)<div><dt class="text-texto-suave">Nome fantasia</dt><dd class="font-medium">{{ $entidade->sigla }}</dd></div>@endif
                @if ($entidade->cnpj)<div><dt class="text-texto-suave">CNPJ</dt><dd class="font-medium">{{ $entidade->cnpj }}</dd></div>@endif
                @if ($entidade->data_fundacao)<div><dt class="text-texto-suave">Fundação</dt><dd class="font-medium">{{ Formatar::data($entidade->data_fundacao) }}</dd></div>@endif
                @if ($entidade->site)<div><dt class="text-texto-suave">Site</dt><dd class="font-medium"><a class="link" href="{{ $entidade->site }}">{{ $entidade->site }}</a></dd></div>@endif
                @if ($entidade->finalidade)
                    <div class="sm:col-span-2"><dt class="text-texto-suave">Finalidade</dt><dd class="whitespace-pre-line">{{ $entidade->finalidade }}</dd></div>
                @endif
            </dl>
        </section>

        <section class="cartao" aria-labelledby="titulo-contato">
            <h2 id="titulo-contato" class="text-lg font-semibold mb-4">Endereço e atendimento</h2>
            <dl class="space-y-3 text-sm">
                @if ($entidade->endereco)<div><dt class="text-texto-suave">Endereço</dt><dd>{{ $entidade->endereco }}<br>{{ $entidade->cidade_uf }} {{ $entidade->cep ? '— CEP '.$entidade->cep : '' }}</dd></div>@endif
                @if ($entidade->telefone)<div><dt class="text-texto-suave">Telefone</dt><dd>{{ $entidade->telefone }}</dd></div>@endif
                @if ($entidade->email)<div><dt class="text-texto-suave">E-mail</dt><dd><a class="link" href="mailto:{{ $entidade->email }}">{{ $entidade->email }}</a></dd></div>@endif
                @if ($entidade->horario_atendimento)<div><dt class="text-texto-suave">Horário</dt><dd>{{ $entidade->horario_atendimento }}</dd></div>@endif
                @if ($entidade->responsavel_transparencia)<div><dt class="text-texto-suave">Responsável pela transparência</dt><dd>{{ $entidade->responsavel_transparencia }}</dd></div>@endif
            </dl>
        </section>
    </div>

    <section class="mt-8" aria-labelledby="titulo-dirigentes">
        <h2 id="titulo-dirigentes" class="text-xl font-semibold mb-4">Dirigentes e conselheiros</h2>
        @forelse ($dirigentes as $orgao => $membros)
            <h3 class="font-semibold text-primaria-escura mt-6 mb-2">{{ $orgao }}</h3>
            <div class="overflow-x-auto">
                <table class="tabela">
                    <caption class="sr-only">{{ $orgao }}</caption>
                    <thead><tr><th scope="col">Nome</th><th scope="col">Cargo</th><th scope="col">Mandato</th></tr></thead>
                    <tbody>
                        @foreach ($membros as $membro)
                            <tr>
                                <td data-label="Nome" class="font-medium">{{ $membro->nome }}</td>
                                <td data-label="Cargo">{{ $membro->cargo }}</td>
                                <td data-label="Mandato">{{ Formatar::data($membro->mandato_inicio) }} a {{ Formatar::data($membro->mandato_fim) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <x-portal.vazio>A composição da diretoria ainda não foi cadastrada.</x-portal.vazio>
        @endforelse
    </section>

    <section class="mt-8" aria-labelledby="titulo-docs-inst">
        <h2 id="titulo-docs-inst" class="text-xl font-semibold mb-4">Estatuto, regulamentos e certidões</h2>
        @include('portal.partials.lista-documentos', ['documentos' => $documentos])
    </section>
</x-portal.layout>
