# Implantação na Hostinger (hospedagem compartilhada)

Guia para colocar o portal no ar num plano compartilhado básico da Hostinger. Os nomes dos menus do hPanel podem mudar um pouco com o tempo.

> **Antes de começar**, confira no hPanel se o seu plano tem **Acesso SSH** (*Sites → Gerenciar → Avançado → Acesso SSH*). Com SSH, a instalação e as atualizações ficam muito mais simples (Caminho A). Sem SSH, use o Caminho B.

## 1. Preparar a hospedagem (uma vez)

1. **Versão do PHP** — *Avançado → Configuração do PHP*: escolha **PHP 8.3** (ou mais nova).
   Na aba de extensões, confirme que estão ativas: `intl`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip`, `gd`.
2. **Banco de dados** — *Bancos de dados → Gerenciamento*: crie um banco MySQL e um usuário. Anote **nome do banco, usuário e senha** (o host é `localhost`).
3. **SSL** — *Segurança → SSL*: ative o certificado gratuito para o domínio.
4. **E-mail (opcional)** — crie uma conta, por exemplo `portal@suaassociacao.org.br`, para o portal avisar sobre novos pedidos de informação.

## 2. Caminho A — com SSH e Git (recomendado)

Conecte-se pelo SSH (o hPanel mostra o comando, algo como `ssh -p 65002 u123456789@IP`).

```bash
cd ~/domains/SEUDOMINIO.org.br

# Baixa o código (repositório privado: cadastre a chave SSH do servidor no GitHub em Settings → Deploy keys)
git clone git@github.com:jornalgazetadoriopardo-sys/portaltransparencia.git portal
cd portal

composer2 install --no-dev --optimize-autoloader     # na Hostinger o comando costuma ser "composer2"
cp .env.example .env
php artisan key:generate
nano .env        # preencha conforme a seção 4 abaixo
php artisan migrate --force
php artisan db:seed --force                           # cria o registro da entidade e perguntas frequentes
php artisan storage:link
php artisan make:filament-user                        # seu login do painel
php artisan optimize
```

Agora aponte o site para a pasta `public` do projeto, trocando `public_html` por um atalho:

```bash
cd ~/domains/SEUDOMINIO.org.br
mv public_html public_html_antigo      # guarda o conteúdo antigo (pode apagar depois de conferir)
ln -s portal/public public_html
```

Pronto: abra `https://SEUDOMINIO.org.br` e `https://SEUDOMINIO.org.br/admin`.

**Para atualizar depois** (quando houver mudanças no código):

```bash
cd ~/domains/SEUDOMINIO.org.br/portal
bash scripts/atualizar-servidor.sh
```

## 3. Caminho B — sem SSH (Gerenciador de Arquivos)

1. No seu computador (com PHP e Composer instalados), na pasta do projeto, rode `bash scripts/gerar-pacote.sh`. Isso gera o arquivo `portal.zip` já com as dependências.
2. No hPanel, abra o **Gerenciador de Arquivos**, entre em `public_html`, envie o `portal.zip` e use **Extrair**. O arquivo `.htaccess` da raiz do projeto encaminha tudo para a pasta `public/` e bloqueia o acesso ao `.env` e às demais pastas.
3. Copie `.env.example` para `.env` e edite pelo próprio Gerenciador de Arquivos (seção 4). Em `APP_KEY`, cole uma chave gerada no seu computador com `php artisan key:generate --show`.
4. Para rodar os comandos de instalação sem SSH, use **Avançado → Tarefas Cron**: crie uma tarefa "a cada minuto" com o comando abaixo, aguarde 1 a 2 minutos e **apague a tarefa** em seguida.

   ```
   cd ~/domains/SEUDOMINIO.org.br/public_html && php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan optimize
   ```

5. Para criar o primeiro usuário do painel, crie outra tarefa temporária (troque nome, e-mail e senha) e apague-a depois de executar:

   ```
   cd ~/domains/SEUDOMINIO.org.br/public_html && php artisan make:filament-user --name="Seu Nome" --email="voce@suaassociacao.org.br" --password="UmaSenhaForte123"
   ```

Para atualizar no Caminho B, gere um novo pacote, envie e extraia por cima (sem apagar o `.env` nem `storage/app/public`, onde ficam os PDFs enviados) e rode a tarefa Cron do passo 4 de novo.

## 4. Configuração do `.env` em produção

```ini
APP_NAME="Portal da Transparência"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://SEUDOMINIO.org.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_portal
DB_USERNAME=u123456789_portal
DB_PASSWORD=senha-do-banco

SESSION_SECURE_COOKIE=true

# E-mail (SMTP da Hostinger) — opcional, para avisos de novos pedidos
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=portal@suaassociacao.org.br
MAIL_PASSWORD=senha-do-email
MAIL_FROM_ADDRESS=portal@suaassociacao.org.br
PORTAL_EMAIL_PEDIDOS=secretaria@suaassociacao.org.br
```

**Importante:** `APP_DEBUG=false` em produção, sempre. Sempre que mudar o `.env`, rode `php artisan optimize` (ou a tarefa Cron) para recarregar as configurações.

## 5. Primeiros passos no painel

1. *Institucional → Dados da entidade*: razão social, CNPJ, endereço, horário de atendimento e logotipo.
2. *Dirigentes e conselheiros*: diretoria e Conselho Fiscal com os mandatos.
3. *Documentos*: estatuto, ata de eleição da diretoria, regulamento de compras, certidões, balanços e pareceres.
4. *Parcerias e convênios*: cada termo ou convênio, com o PDF do instrumento e o plano de trabalho.
5. *Receitas* e *Despesas*: lançamentos (pelo menos os pagos com recursos públicos; o ideal é lançar tudo).
6. Confira o resultado em **Ver portal público**.

## 6. Cópias de segurança

A Hostinger faz backups automáticos (*Arquivos → Backups*), mas recomenda-se baixar periodicamente:
- o banco de dados (*Bancos de dados → phpMyAdmin → Exportar*);
- a pasta `storage/app/public` (PDFs enviados pelo painel).

## Problemas comuns

| Sintoma | Solução |
|---|---|
| Erro 500 logo após instalar | Confira o `.env` (dados do banco, `APP_KEY` preenchida) e as permissões de `storage/` e `bootstrap/cache/` (755). Veja o erro em `storage/logs/laravel.log`. |
| PDFs enviados não abrem (404) | Falta o atalho de arquivos: rode `php artisan storage:link`. |
| Painel sem estilo | Rode `php artisan filament:assets` e `php artisan optimize`. |
| Página sem estilo no site público | A pasta `public/build` não foi enviada. Ela vem no repositório; confira se existe no servidor. |
| "Specified key was too long" ao migrar | Banco MySQL antigo; o projeto já limita os índices a 191 caracteres. Rode `php artisan migrate:fresh --force` numa instalação nova. |
| Upload de arquivo grande falha | Em *Configuração do PHP*, aumente `upload_max_filesize` e `post_max_size` (ex.: 32M). |
