#!/usr/bin/env bash
# Atualiza o portal no servidor (rodar via SSH/Terminal, dentro da pasta do projeto).
#   bash scripts/atualizar-servidor.sh
set -euo pipefail

COMPOSER=$(command -v composer2 || command -v composer)

# Se qualquer passo falhar, tira o site da manutenção e mostra onde parou.
falhou() {
    echo
    echo "ERRO no comando: $BASH_COMMAND"
    php artisan up || true
    echo "O portal voltou ao ar com a versão anterior. Envie esta mensagem para quem cuida do sistema."
}
trap falhou ERR

php artisan down --retry=30 || true
git pull --ff-only
$COMPOSER install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan filament:assets
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize || true
php artisan up

echo "Portal atualizado."
