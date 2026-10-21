@use('App\Support\Formatar')
<x-portal.layout titulo="Prestação de contas">
    <x-portal.cabecalho titulo="Prestação de contas" :atualizado-em="$atualizadoEm">
        Demonstrações contábeis, pareceres do Conselho Fiscal, relatórios de atividades e a situação da prestação de contas de cada parceria.
    </x-portal.cabecalho>

    <section aria-labelledby="titulo-situacao" class="mb-10">
        <h2 id="titulo-situacao" class="text-xl font-semibold mb-4">Situação por parceria</h2>
        @if ($parcerias->isEmpty())
            <x-portal.vazio>Nenhuma parceria publicada.</x-portal.vazio>
        @else
            <div class="overflow-x-auto">
                <table class="tabela">
                    <caption class="sr-only">Situação da prestação de contas de cada parceria</caption>
                    <thead><tr><th scope="col">Parceria</th><th scope="col">Concedente</th><th scope="col">Situação</th><th scope="col">Data prevista</th><th scope="col">Apresentada em</th><th scope="col">Prazo de análise</th></tr></thead>
                    <tbody>
                        @foreach ($parcerias as $parceria)
                            <tr>
                                <td data-label="Parceria"><a class="link font-medium" href="{{ route('parcerias.show', $parceria) }}">{{ $parceria->identificacao }}</a></td>
                                <td data-label="Concedente">{{ $parceria->orgao_concedente }}</td>
                                <td data-label="Situação"><span class="selo-status">{{ $parceria->prestacao_situacao->getLabel() }}</span></td>
                                <td data-label="Data prevista">{{ Formatar::data($parceria->prestacao_data_prevista) }}</td>
                                <td data-label="Apresentada em">{{ Formatar::data($parceria->prestacao_data_apresentacao) }}</td>
                                <td data-label="Prazo de análise">{{ $parceria->prestacao_prazo_analise_dias ? $parceria->prestacao_prazo_analise_dias.' dias' : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section aria-labelledby="titulo-anual">
        <h2 id="titulo-anual" class="text-xl font-semibold mb-4">Documentos por exercício</h2>
        @forelse ($documentosPorAno as $ano => $documentos)
            <details class="mb-3 rounded-lg border border-borda bg-white" @if ($loop->first) open @endif>
                <summary class="cursor-pointer select-none p-4 font-semibold">Exercício {{ $ano }} <span class="text-sm font-normal text-texto-suave">({{ $documentos->count() }} documento(s))</span></summary>
                <div class="px-4 pb-4">
                    @include('portal.partials.lista-documentos', ['documentos' => $documentos])
                </div>
            </details>
        @empty
            <x-portal.vazio>Nenhum documento de prestação de contas publicado.</x-portal.vazio>
        @endforelse
    </section>
</x-portal.layout>
