<x-portal.layout titulo="Dados abertos">
    <x-portal.cabecalho titulo="Dados abertos">
        Os dados do portal podem ser baixados em formatos abertos e legíveis por máquina, para reutilização livre.
        Os filtros de cada página também valem para o download (ex.: <code>?ano={{ now()->year }}</code>).
    </x-portal.cabecalho>

    @php
        $conjuntos = [
            ['Parcerias e convênios', 'parcerias.exportar', 'Instrumentos, valores e situação da prestação de contas.'],
            ['Receitas', 'receitas.exportar', 'Recursos recebidos, por data e fonte.'],
            ['Despesas', 'despesas.exportar', 'Pagamentos realizados, por categoria e favorecido.'],
            ['Compras e contratações', 'compras.exportar', 'Processos de cotação e contratação.'],
            ['Documentos', 'documentos.exportar', 'Lista de documentos publicados e seus endereços.'],
        ];
    @endphp
    <div class="overflow-x-auto">
        <table class="tabela">
            <caption class="sr-only">Conjuntos de dados disponíveis</caption>
            <thead><tr><th scope="col">Conjunto</th><th scope="col">Conteúdo</th><th scope="col">Formatos</th></tr></thead>
            <tbody>
                @foreach ($conjuntos as [$nome, $rota, $texto])
                    <tr>
                        <td data-label="Conjunto" class="font-medium">{{ $nome }}</td>
                        <td data-label="Conteúdo">{{ $texto }}</td>
                        <td data-label="Formatos" class="whitespace-nowrap">
                            <a class="link" href="{{ route($rota) }}">CSV</a> ·
                            <a class="link" href="{{ route($rota, ['formato' => 'json']) }}">JSON</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="mt-4 text-sm text-texto-suave">Os arquivos CSV usam ponto e vírgula como separador e codificação UTF-8, e abrem diretamente no Excel e no LibreOffice. CPFs de pessoas físicas são exibidos mascarados, conforme a LGPD.</p>
</x-portal.layout>
