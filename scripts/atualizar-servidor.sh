#!/usr/bin/env bash
# Atualiza o portal no servidor da Hostinger (rodar via SSH, dentro da pasta do projeto).
#   bash scripts/atualizar-servidor.sh
set -euo pipefail

COMPOSER=$(command -v composer2 || command -v composer)

php artisan down --retry=30 || true
git pull --ff-only
$COMPOSER install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize || true
php artisan up

echo "Portal atualizado."
