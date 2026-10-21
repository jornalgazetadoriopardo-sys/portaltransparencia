# Implantação em cPanel (conta compartilhada com outros sites)

Guia para quando a hospedagem usa **cPanel** e vários sites dividem a mesma conta, cada um numa pasta dentro do diretório pessoal. Exemplo usado aqui:

- conta: `/home/santacas`
- pasta do portal: `/home/santacas/edsj`

Troque `santacas`, `edsj` e `SEUDOMINIO.org.br` pelos valores reais. Os outros sites da conta (`armtrs`, `autle` etc.) **não são afetados**: tudo acontece dentro de `edsj` e num banco de dados novo.

> **Antes de começar:** veja se `edsj` já tem um site no ar. Se tiver, baixe uma cópia (botão **Compress** do Gerenciador de Arquivos, depois **Download**) antes de mexer.

## 1. Preparar a conta (uma vez)

1. **Versão do PHP**: em *Software → Select PHP Version* (seletor do CloudLinux), escolha **8.3** ou mais nova. Na mesma tela, marque as extensões `intl`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip` e `gd`.
   - Se a tela *MultiPHP Manager* aparecer, confira também se o domínio do portal está em 8.3. Lá a versão pode ser escolhida por domínio.
   - Atenção: no seletor do CloudLinux, a versão costuma valer **para a conta inteira**. Antes de mudar, confirme que os outros sites funcionam em PHP 8.3. Se não funcionarem, peça ao suporte para definir o PHP 8.3 só para o domínio do portal.
2. **Banco de dados**: em *Databases → MySQL Database Wizard*, crie o banco (por exemplo `santacas_portal`) e um usuário com **todos os privilégios** nele. Anote o nome do banco, o usuário e a senha. O host é `localhost`.
3. **SSL**: em *Security → SSL/TLS Status*, confirme que o domínio tem certificado (AutoSSL).
4. **E-mail (opcional)**: em *Email → Email Accounts*, crie por exemplo `portal@SEUDOMINIO.org.br` para os avisos de pedidos de informação.

## 2. Para onde o domínio aponta

O Laravel precisa que o domínio abra a pasta `public/` do projeto. No cPanel há duas formas:

**Forma 1 (recomendada): mudar a raiz do domínio.** Em *Domains → Domains*, clique em **Manage** no domínio do portal e mude o *Document Root* de `edsj` para **`edsj/public`**. Assim, `.env`, `vendor/` e `storage/` ficam fora do alcance do navegador.

**Forma 2: se o cPanel não deixar mudar a raiz.** Deixe a raiz em `edsj`. O arquivo `.htaccess` que já vem na raiz do projeto encaminha tudo para `public/` e bloqueia o acesso direto a arquivos ocultos, como o `.env`.

## 3. Enviar os arquivos

### Caminho A: sem terminal (Gerenciador de Arquivos)

1. No seu computador (com PHP 8.3 e Composer), na pasta do projeto, rode `bash scripts/gerar-pacote.sh`. Isso gera o `portal.zip` (cerca de 60 MB) já com as dependências.
2. No **File Manager**, abra `edsj`, clique em **Upload**, envie o `portal.zip`, clique nele com o botão direito e escolha **Extract**. A extração deve ficar **direto dentro de `edsj`**: você precisa ver `edsj/app`, `edsj/public`, `edsj/artisan` etc. Depois apague o `portal.zip`.
   - Ligue *Settings → Show Hidden Files* no Gerenciador para ver o `.htaccess` e o `.env`.
3. Copie `edsj/.env.example` para `edsj/.env` e edite (seção 4). Em `APP_KEY`, cole uma chave gerada no seu computador com `php artisan key:generate --show`.

### Caminho B: com terminal (*Advanced → Terminal* ou SSH)

```bash
cd ~/edsj
git clone https://github.com/jornalgazetadoriopardo-sys/portaltransparencia.git .   # a pasta precisa estar vazia
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env       # seção 4
```

Se o repositório for privado, o `git clone` vai pedir usuário e um *token* do GitHub. Outra opção é usar *Files → Git Version Control* do cPanel. Se o comando `composer` não existir no servidor, use o Caminho A.

## 4. Arquivo `.env` em produção

```ini
APP_NAME="Portal da Transparência"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...colar-a-chave-gerada...
APP_URL=https://SEUDOMINIO.org.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=santacas_portal
DB_USERNAME=santacas_portal
DB_PASSWORD=senha-do-banco

SESSION_SECURE_COOKIE=true

# Opcional: avisos de novos pedidos de informação
MAIL_MAILER=smtp
MAIL_HOST=mail.SEUDOMINIO.org.br
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=portal@SEUDOMINIO.org.br
MAIL_PASSWORD=senha-do-email
MAIL_FROM_ADDRESS=portal@SEUDOMINIO.org.br
PORTAL_EMAIL_PEDIDOS=secretaria@SEUDOMINIO.org.br
```

## 5. Instalar (criar tabelas, atalho dos arquivos, primeiro usuário)

**Com terminal:**

```bash
cd ~/edsj
php -v                      # precisa mostrar 8.3 ou mais nova
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan make:filament-user
php artisan optimize
```

**Sem terminal:** use *Advanced → Cron Jobs*. Crie uma tarefa com *Once Per Minute* e o comando abaixo, espere 2 minutos, confira o resultado e **apague a tarefa**:

```
cd /home/santacas/edsj && (php -v && php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan optimize) > storage/logs/instalacao.txt 2>&1
```

Depois, crie outra tarefa temporária para o primeiro usuário do painel (troque os dados) e apague-a assim que executar:

```
cd /home/santacas/edsj && php artisan make:filament-user --name="Seu Nome" --email="voce@SEUDOMINIO.org.br" --password="UmaSenhaForte123"
```

> Se o arquivo `storage/logs/instalacao.txt` mostrar erro de versão do PHP, troque `php` pelo caminho completo da versão 8.3. No CloudLinux costuma ser `/opt/alt/php83/usr/bin/php`, e o suporte da hospedagem confirma o caminho exato.

Pronto: abra `https://SEUDOMINIO.org.br` (portal) e `https://SEUDOMINIO.org.br/admin` (painel).

## 6. Atualizar depois

- **Com terminal:** `cd ~/edsj && bash scripts/atualizar-servidor.sh`
- **Sem terminal:** gere um novo `portal.zip`, envie e extraia por cima em `edsj`. **Não apague** o `.env` nem a pasta `storage/app/public`, onde ficam os PDFs enviados pelo painel. Depois rode de novo a tarefa Cron da seção 5 (só a primeira).

## 7. Cópias de segurança

- Banco: *Databases → phpMyAdmin → Export*.
- Arquivos enviados: pasta `edsj/storage/app/public`.
- O *Backup* do cPanel também inclui tudo, mas a cópia completa contém todos os sites da conta.

## Problemas comuns

| Sintoma | Solução |
|---|---|
| Erro 500 | Confira o `.env` (banco e `APP_KEY`) e as permissões de `storage/` e `bootstrap/cache/` (755). O erro detalhado fica em `edsj/storage/logs/laravel.log`. |
| Aparece a lista de pastas ou "Index of" | O domínio não está apontando para `public/`: refaça a seção 2. Se usou a Forma 2, confira se o `.htaccess` está na raiz de `edsj`. |
| PDFs enviados dão 404 | Falta o atalho: rode `php artisan storage:link`. |
| Painel sem estilo | Rode `php artisan filament:assets` e `php artisan optimize`. |
| Upload de arquivo grande falha | Em *Select PHP Version → Options*, aumente `upload_max_filesize` e `post_max_size` (por exemplo, 32M). |
| Outro site da conta parou após trocar o PHP | O seletor mudou a versão da conta toda. Volte a versão anterior e peça ao suporte PHP 8.3 só para o domínio do portal. |
