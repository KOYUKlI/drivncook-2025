#!/usr/bin/env bash
set -euo pipefail

has_cmd(){ command -v "$1" >/dev/null 2>&1; }

echo "[1/8] Composer create-project Laravel 12"
if has_cmd composer; then
  [ -f artisan ] || composer create-project laravel/laravel . "12.*"
else
  docker run --rm -u $(id -u):$(id -g) -v $PWD:/app -w /app composer:2 \
    sh -lc '[ -f artisan ] || composer create-project laravel/laravel . "12.*"'
fi

echo "[2/8] Sail install"
(has_cmd php && php artisan -V >/dev/null) || { echo "PHP requis pour artian"; exit 1; }
[ -f docker-compose.yml ] || (composer require laravel/sail --dev && php artisan sail:install --with=mysql,redis,mailpit)

echo "[3/8] Containers up"
./vendor/bin/sail up -d

echo "[4/8] Breeze + Front"
composer require laravel/breeze --dev || true
php artisan breeze:install blade || true
./vendor/bin/sail npm ci
./vendor/bin/sail npm run build

echo "[5/8] Cashier + Dompdf + Spatie"
composer require laravel/cashier:^15.0 barryvdh/laravel-dompdf:^3.0 spatie/laravel-permission || true
php artisan vendor:publish --tag="cashier-migrations" || true
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider" --tag=config || true
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" || true

echo "[6/8] Qualité"
composer require --dev pestphp/pest pestphp/pest-plugin-laravel laravel/pint phpstan/phpstan nunomaduro/larastan || true
php artisan pest:install || true

echo "[7/8] Migrate + Seed"
./vendor/bin/sail artisan migrate --force || true
./vendor/bin/sail artisan db:seed || true

echo "[8/8] Storage link"
./vendor/bin/sail artisan storage:link || true

echo "[Post] Optimize clear"
./vendor/bin/sail artisan optimize:clear || true

echo "Bootstrap terminé ✅"
