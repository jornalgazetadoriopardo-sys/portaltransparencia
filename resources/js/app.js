// Menu no celular
const botaoMenu = document.querySelector('[data-menu-botao]');
const listaMenu = document.getElementById('menu-lista');

botaoMenu?.addEventListener('click', () => {
    const aberto = botaoMenu.getAttribute('aria-expanded') === 'true';
    botaoMenu.setAttribute('aria-expanded', String(!aberto));
    listaMenu.classList.toggle('hidden', aberto);
});

// Preferências de acessibilidade, guardadas apenas no navegador do visitante.
const raiz = document.documentElement;

function salvar(chave, valor) {
    try {
        if (valor === null) localStorage.removeItem(chave);
        else localStorage.setItem(chave, valor);
    } catch (e) {
        // Navegação privada: a preferência vale só para esta página.
    }
}

document.querySelectorAll('[data-fonte]').forEach((botao) => {
    botao.addEventListener('click', () => {
        const passo = Number(botao.dataset.fonte);
        const atual = parseInt(raiz.style.fontSize, 10) || 100;
        const novo = passo === 0 ? 100 : Math.min(150, Math.max(80, atual + passo));
        raiz.style.fontSize = novo === 100 ? '' : novo + '%';
        salvar('pt-fonte', novo === 100 ? null : String(novo));
    });
});

const botaoContraste = document.querySelector('[data-contraste]');
botaoContraste?.setAttribute('aria-pressed', String(raiz.classList.contains('alto-contraste')));
botaoContraste?.addEventListener('click', () => {
    const ativo = raiz.classList.toggle('alto-contraste');
    botaoContraste.setAttribute('aria-pressed', String(ativo));
    salvar('pt-contraste', ativo ? '1' : null);
});
