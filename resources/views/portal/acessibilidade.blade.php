<x-portal.layout titulo="Acessibilidade">
    <x-portal.cabecalho titulo="Acessibilidade" />
    <div class="cartao max-w-3xl space-y-4">
        <p>Este portal foi construído seguindo as recomendações do eMAG (Modelo de Acessibilidade em Governo Eletrônico) e das diretrizes internacionais WCAG.</p>
        <h2 class="text-lg font-semibold">Atalhos de teclado</h2>
        <ul class="list-disc pl-5 space-y-1">
            <li><kbd>Alt</kbd> + <kbd>1</kbd>: ir para o conteúdo</li>
            <li><kbd>Alt</kbd> + <kbd>2</kbd>: ir para o menu</li>
            <li><kbd>Alt</kbd> + <kbd>3</kbd>: ir para a busca</li>
        </ul>
        <p class="text-sm text-texto-suave">No Firefox use <kbd>Alt</kbd> + <kbd>Shift</kbd> + número; no Mac, <kbd>Control</kbd> + <kbd>Option</kbd> + número.</p>
        <h2 class="text-lg font-semibold">Recursos disponíveis</h2>
        <ul class="list-disc pl-5 space-y-1">
            <li>Botões <strong>A+</strong> e <strong>A-</strong> no topo para aumentar ou diminuir o texto.</li>
            <li>Modo <strong>alto contraste</strong>.</li>
            <li>Tradução para Libras com o <strong>VLibras</strong> (ícone no canto da tela).</li>
            <li>Navegação completa pelo teclado e compatível com leitores de tela.</li>
            <li>Layout adaptado para celulares.</li>
        </ul>
        <p>Encontrou alguma barreira de acesso? <a class="link" href="{{ route('pedido.create') }}">Fale conosco</a>.</p>
    </div>
</x-portal.layout>
