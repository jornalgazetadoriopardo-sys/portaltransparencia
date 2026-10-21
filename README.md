# Portal da Transparência

Portal da Transparência para **associação sem fins lucrativos** que recebe recursos públicos, feito para rodar em **hospedagem compartilhada da Hostinger** (PHP + MySQL, sem Node.js no servidor).

Atende à **Lei nº 12.527/2011 (LAI)** e ao **art. 11 da Lei nº 13.019/2014 (MROSC)**.

## O que o portal tem

**Site público**
- **Início** com números do ano (receitas, despesas, parcerias em execução) e atalhos para as seções.
- **Institucional**: dados da entidade, diretoria e conselhos, estatuto, regulamentos e certidões.
- **Parcerias e convênios** com todos os campos do art. 11 do MROSC: instrumento, data, órgão concedente, objeto, valor total e liberado, situação da prestação de contas (datas, prazo de análise, resultado) e equipe paga com recursos da parceria (por função, sem nomes).
- **Receitas** e **Despesas** com filtros (ano, fonte/categoria, parceria, palavra-chave), totais e gráfico por categoria.
- **Compras e contratações** (cotações, seleção de fornecedores).
- **Prestação de contas**: situação por parceria e documentos por exercício (balanço, parecer do Conselho Fiscal, relatórios, atas).
- **Documentos** com filtros por categoria e ano.
- **Pedido de informação** com protocolo e consulta da resposta pelo cidadão.
- **Busca geral**, **perguntas frequentes**, **dados abertos** (CSV e JSON em todas as tabelas), páginas de **acessibilidade** e **privacidade/LGPD**.

**Acessibilidade e usabilidade**
- Layout responsivo (no celular, as tabelas viram cartões).
- Atalhos de teclado (Alt+1/2/3), aumento de fonte, alto contraste e widget **VLibras**.
- Página "atualizado em" em cada seção; impressão/PDF.
- CPF de pessoa física é mascarado automaticamente (LGPD).

**Painel administrativo** (`/admin`, feito com Filament)
- Cadastros de entidade, dirigentes, parcerias (com equipe), receitas, despesas, compras, documentos, perguntas frequentes e usuários.
- Envio de PDFs, filtros, totais e busca.
- Caixa de **pedidos de informação** com contador de pendentes e campo de resposta.

## Tecnologia

- PHP 8.3+, Laravel 13, Filament 5 (painel), Blade + Tailwind CSS 4 (site público).
- Banco MySQL/MariaDB (produção) ou SQLite (desenvolvimento).
- Os arquivos CSS/JS já vão compilados em `public/build`, então **não é preciso Node.js no servidor**.

## Rodar no computador (desenvolvimento)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed --seeder=DemonstracaoSeeder   # carrega dados fictícios
php artisan storage:link
php artisan make:filament-user                            # cria seu login do painel
php artisan serve
```

Acesse http://localhost:8000 (portal) e http://localhost:8000/admin (painel).

Se alterar o visual (arquivos em `resources/css`, `resources/js` ou nas views), recompile com `npm install && npm run build` e faça commit da pasta `public/build`.

Testes: `php artisan test`

## Colocar no ar na Hostinger

Siga o passo a passo em **[docs/IMPLANTACAO-HOSTINGER.md](docs/IMPLANTACAO-HOSTINGER.md)**.

## Personalizar

- **Cores**: variáveis `--color-*` no início de `resources/css/app.css` (depois `npm run build`).
- **Nome, logotipo, contatos**: pelo painel, em *Institucional → Dados da entidade*.
- **Prazo de resposta e e-mail de aviso dos pedidos**: `config/portal.php` e a variável `PORTAL_EMAIL_PEDIDOS` do `.env`.
