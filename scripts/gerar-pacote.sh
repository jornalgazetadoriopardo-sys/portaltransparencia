#!/usr/bin/env bash
# Gera portal.zip pronto para enviar pelo Gerenciador de Arquivos da Hostinger,
# para quem não usa SSH/Git. Inclui vendor/ (dependências) e os arquivos do Filament.
# Rode no seu computador, na raiz do projeto:  bash scripts/gerar-pacote.sh
set -euo pipefail

RAIZ=$(cd "$(dirname "$0")/.." && pwd)
DESTINO="$RAIZ/pacote"

rm -rf "$DESTINO" "$RAIZ/portal.zip"
mkdir -p "$DESTINO"

git -C "$RAIZ" archive HEAD | tar -x -C "$DESTINO"

cd "$DESTINO"
cp .env.example .env
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan filament:assets
rm .env

zip -qr "$RAIZ/portal.zip" . -x "tests/*" "node_modules/*" "*/.git/*"
cd "$RAIZ" && rm -rf "$DESTINO"

echo "Pacote gerado: $RAIZ/portal.zip"
