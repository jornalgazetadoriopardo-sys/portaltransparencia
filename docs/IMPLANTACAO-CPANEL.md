# Implantação em cPanel: educandariosaojose.ong.br/transparencia

Passo a passo para publicar o portal em **https://educandariosaojose.ong.br/transparencia**, numa conta cPanel compartilhada com outros sites.

| Item | Valor |
|---|---|
| Conta cPanel | `/home/santacas` |
| Pasta pública do domínio | `/home/santacas/edsj` |
| Código do portal (fora da pasta pública) | `/home/santacas/portal-transparencia` |
| Endereço do portal | `https://educandariosaojose.ong.br/transparencia` |
| Painel administrativo | `https://educandariosaojose.ong.br/transparencia/admin` |

**Como funciona:** o código fica **fora** da pasta pública, e dentro de `edsj` existe só um atalho (link simbólico) chamado `transparencia`, que aponta para a pasta `public/` do projeto. Assim, o `.env` (com as senhas) e o resto do código nunca ficam acessíveis pelo navegador, e a raiz do domínio continua livre para o site institucional.

Os outros sites da conta (`armtrs`, `autle` etc.) não são afetados.

## 1. Preparar a conta no cPanel (uma vez)

1. **PHP 8.3**: em *Software → Select PHP Version*, escolha **8.3** (ou mais nova) e marque as extensões `intl`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip` e `gd`. Na aba *Options*, ajuste `upload_max_filesize` e `post_max_size` para **32M**.
   > ⚠️ Nesse seletor, a versão costuma valer para **todos os sites da conta**. Antes de trocar, confirme que os outros sites funcionam em PHP 8.3. Se não funcionarem, peça ao suporte da hospedagem para ativar o PHP 8.3 só no domínio `educandariosaojose.ong.br`.
2. **Banco de dados**: em *Databases → MySQL Database Wizard*, crie o banco (ex.: `santacas_transparencia`) e um usuário com **ALL PRIVILEGES**. Anote o nome do banco, o usuário e a senha.
3. **SSL**: em *Security → SSL/TLS Status*, confirme que `educandariosaojose.ong.br` tem o cadeado (AutoSSL).
4. **E-mail (opcional)**: em *Email → Email Accounts*, crie `transparencia@educandariosaojose.ong.br` para receber os avisos de pedidos de informação.

## 2. Instalar pelo Terminal

Abra *Advanced → Terminal* e rode os blocos abaixo, um de cada vez.

### 2.1 Conferir PHP e Composer

```bash
php -v          # precisa mostrar 8.3.x ou mais nova
composer -V     # se der "command not found", rode o bloco abaixo
```

Se não houver Composer, instale uma cópia só para a sua conta:

```bash
mkdir -p ~/bin && cd ~/bin
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --filename=composer && rm composer-setup.php
export PATH="$HOME/bin:$PATH" && echo 'export PATH="$HOME/bin:$PATH"' >> ~/.bashrc
composer -V
```

### 2.2 Baixar o código

```bash
cd ~
git clone https://github.com/jornalgazetadoriopardo-sys/portaltransparencia.git portal-transparencia
cd portal-transparencia
```

Se o repositório for **privado**, o Git pede usuário e senha. Na senha, use um *token* do GitHub, não a senha da conta: em *GitHub → Settings → Developer settings → Personal access tokens → Fine-grained tokens*, gere um token com acesso de leitura (*Contents: Read-only*) só a este repositório.

### 2.3 Instalar as dependências e configurar

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env
```

No `nano`, ajuste as linhas abaixo e salve com `Ctrl+O`, `Enter`, `Ctrl+X`:

```ini
APP_NAME="Portal da Transparência"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://educandariosaojose.ong.br/transparencia

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=santacas_transparencia
DB_USERNAME=santacas_transparencia
DB_PASSWORD=senha-do-banco

SESSION_PATH=/transparencia
SESSION_SECURE_COOKIE=true

# Opcional: aviso por e-mail de novos pedidos de informação
MAIL_MAILER=smtp
MAIL_HOST=mail.educandariosaojose.ong.br
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=transparencia@educandariosaojose.ong.br
MAIL_PASSWORD=senha-do-email
MAIL_FROM_ADDRESS=transparencia@educandariosaojose.ong.br
PORTAL_EMAIL_PEDIDOS=secretaria@educandariosaojose.ong.br
```

As linhas `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` vêm comentadas no arquivo, com `#` na frente. Apague o `#` ao preencher.

### 2.4 Criar as tabelas, o primeiro usuário e otimizar

```bash
php artisan migrate --force
php artisan db:seed --force          # cria o cadastro da entidade e as perguntas frequentes
```

Atalho para os arquivos enviados pelo painel (PDFs e imagens). O `php artisan storage:link` costuma falhar em hospedagem compartilhada, que bloqueia as funções `symlink`/`exec` do PHP, então crie o atalho direto:

```bash
ln -s /home/santacas/portal-transparencia/storage/app/public /home/santacas/portal-transparencia/public/storage
```

Primeiro administrador do painel. Rode este comando **sozinho**, sem colar outros junto: ele pergunta nome, e-mail e senha, e a senha não aparece enquanto você digita.

```bash
php artisan make:filament-user
```

Por fim:

```bash
php artisan optimize
chmod -R 755 storage bootstrap/cache
```

### 2.5 Publicar em /transparencia

```bash
ln -s /home/santacas/portal-transparencia/public /home/santacas/edsj/transparencia
ls -l /home/santacas/edsj        # deve mostrar: transparencia -> /home/santacas/portal-transparencia/public
```

Pronto. Abra:
- https://educandariosaojose.ong.br/transparencia (portal)
- https://educandariosaojose.ong.br/transparencia/admin (painel)

### 2.6 (Opcional) Raiz do domínio

A pasta `edsj` está vazia, então quem acessar `educandariosaojose.ong.br` sem o `/transparencia` verá uma página em branco ou um erro. Enquanto não houver site institucional, dá para mandar esses visitantes para o portal:

```bash
cat > /home/santacas/edsj/.htaccess <<'EOF'
RewriteEngine On
RewriteRule ^$ /transparencia/ [R=302,L]
EOF
```

Quando o site institucional for publicado em `edsj`, apague esse `.htaccess` (ou ajuste-o) e coloque um link para "Transparência" no menu do site.

## 3. Atualizar o portal depois

Quando houver mudanças no código:

```bash
cd ~/portal-transparencia
bash scripts/atualizar-servidor.sh
```

O script coloca o portal em manutenção por alguns segundos, baixa o código novo, atualiza as dependências e o banco e volta ao ar.

## 4. Primeiros passos no painel

1. *Institucional → Dados da entidade*: razão social, CNPJ, endereço, horário de atendimento e logotipo.
2. *Dirigentes e conselheiros*: diretoria e Conselho Fiscal, com os mandatos.
3. *Documentos*: estatuto, ata de eleição, regulamento de compras, certidões, balanços e pareceres.
4. *Parcerias e convênios*: cada termo ou convênio, com o PDF do instrumento e o plano de trabalho.
5. *Receitas* e *Despesas*: lançamentos (no mínimo os pagos com recursos públicos).
6. Confira o resultado em **Ver portal público**.

## 5. Cópias de segurança

```bash
# Banco de dados (gera um arquivo .sql.gz na pasta pessoal)
mysqldump -u santacas_transparencia -p santacas_transparencia | gzip > ~/backup-transparencia-$(date +%F).sql.gz

# PDFs e imagens enviados pelo painel
tar czf ~/backup-arquivos-$(date +%F).tar.gz -C ~/portal-transparencia/storage/app public
```

Baixe esses arquivos pelo Gerenciador de Arquivos e guarde fora do servidor. O ideal é fazer isso uma vez por mês.

## Problemas comuns

| Sintoma | Solução |
|---|---|
| Erro 500 | Veja o erro em `~/portal-transparencia/storage/logs/laravel.log`. As causas mais comuns são `.env` errado (dados do banco) ou falta de permissão em `storage/`. Para esta última, rode `chmod -R 755 storage bootstrap/cache`. |
| 403 / "Forbidden" em /transparencia | O servidor não está seguindo o link simbólico. Peça ao suporte para habilitar `SymLinksIfOwnerMatch` para a conta, ou use a alternativa abaixo. |
| PDFs enviados dão 404 | Falta o atalho dos arquivos: `ln -s /home/santacas/portal-transparencia/storage/app/public /home/santacas/portal-transparencia/public/storage` |
| `Call to undefined function ... exec()` no `storage:link` | A hospedagem bloqueia essa função do PHP. Crie o atalho com o comando `ln -s` da linha acima. |
| Links levam para educandariosaojose.ong.br sem o /transparencia | Confira `APP_URL` no `.env` e rode `php artisan optimize`. |
| Login do painel volta para a tela de login | Confira `SESSION_PATH=/transparencia` e `SESSION_SECURE_COOKIE=true` (o site deve estar em https) e rode `php artisan optimize`. |
| Aviso "pdo_mysql skipped as conflicting" no Select PHP Version | É normal: `pdo_mysql` e `nd_pdo_mysql` não podem ficar ligadas juntas. Basta uma delas. Confira com `php -m \| grep -i pdo` (deve listar `pdo_mysql`). Se não listar, marque `nd_pdo_mysql` e `mysqlnd`. |
| Upload de arquivo grande falha | Aumente `upload_max_filesize` e `post_max_size` em *Select PHP Version → Options*. |

**Alternativa sem link simbólico** (se o suporte não permitir): coloque o projeto inteiro em `/home/santacas/edsj/transparencia` em vez de `~/portal-transparencia`. O arquivo `.htaccess` da raiz do projeto encaminha as requisições para `public/` e bloqueia arquivos ocultos como o `.env`. A primeira opção (link simbólico) continua sendo a mais segura.
