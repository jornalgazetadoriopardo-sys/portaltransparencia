<x-portal.layout titulo="Privacidade e LGPD">
    <x-portal.cabecalho titulo="Privacidade e proteção de dados" />
    <div class="cartao max-w-3xl space-y-4">
        <p>A {{ $entidade->nome }} trata dados pessoais conforme a Lei nº 13.709/2018 (LGPD).</p>
        <h2 class="text-lg font-semibold">Pedidos de informação</h2>
        <p>Nome, e-mail e telefone informados no pedido de informação são usados exclusivamente para registrar, responder e comprovar o atendimento do pedido. Esses dados não são publicados nem compartilhados com terceiros.</p>
        <h2 class="text-lg font-semibold">Dados publicados no portal</h2>
        <p>A publicação de parcerias, valores, despesas e dirigentes é exigida pela Lei de Acesso à Informação e pelo Marco Regulatório das Organizações da Sociedade Civil. Para proteger pessoas físicas, CPFs aparecem mascarados e a remuneração da equipe é divulgada por função, sem nomes.</p>
        <h2 class="text-lg font-semibold">Cookies</h2>
        <p>O portal usa apenas um cookie técnico de sessão, necessário para o funcionamento dos formulários. Não utilizamos cookies de rastreamento ou publicidade. As preferências de acessibilidade (tamanho do texto e contraste) ficam salvas apenas no seu navegador.</p>
        @if ($entidade->email)
            <h2 class="text-lg font-semibold">Contato</h2>
            <p>Para exercer seus direitos como titular de dados, escreva para <a class="link" href="mailto:{{ $entidade->email }}">{{ $entidade->email }}</a>.</p>
        @endif
    </div>
</x-portal.layout>
