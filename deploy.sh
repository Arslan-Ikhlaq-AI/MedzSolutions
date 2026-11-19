#!/usr/bin/env bash
#
# Server-side deployment for medzsolutions.com (cPanel shared hosting).
#
# The Laravel app lives in this git checkout (~/repositories/MedzSolutions).
# Only the contents of public/ are copied into ~/public_html, with an
# index.php that points back to this checkout.
#
# Usage (cPanel Terminal):
#   bash ~/repositories/MedzSolutions/deploy.sh            # pull + deploy
#   bash ~/repositories/MedzSolutions/deploy.sh --no-pull  # deploy current checkout
#
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_HTML="${PUBLIC_HTML:-$HOME/public_html}"
BRANCH="${BRANCH:-main}"
APP_URL_DEFAULT="https://medzsolutions.com"
PHP_HANDLER="application/x-httpd-alt-php83"

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$*"; }
fail() { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

cd "$APP_DIR"

# ---------------------------------------------------------------------------
step "Selecting PHP 8.3+"
PHP_BIN="${PHP_BIN:-}"
if [[ -z "$PHP_BIN" ]]; then
    for candidate in /opt/alt/php83/usr/bin/php /opt/alt/php84/usr/bin/php "$(command -v php || true)"; do
        if [[ -n "$candidate" && -x "$candidate" ]] \
            && "$candidate" -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);'; then
            PHP_BIN="$candidate"
            break
        fi
    done
fi
[[ -n "$PHP_BIN" ]] || fail "PHP 8.3+ not found. Set PHP_BIN=/path/to/php and retry."
echo "Using $PHP_BIN ($("$PHP_BIN" -r 'echo PHP_VERSION;'))"

COMPOSER_BIN="${COMPOSER_BIN:-}"
if [[ -z "$COMPOSER_BIN" ]]; then
    for candidate in "$HOME/bin/composer" "$HOME/bin/composer.phar" /opt/cpanel/composer/bin/composer "$(command -v composer || true)"; do
        if [[ -n "$candidate" && -f "$candidate" ]]; then
            COMPOSER_BIN="$candidate"
            break
        fi
    done
fi
if [[ -z "$COMPOSER_BIN" ]]; then
    step "Installing Composer to ~/bin/composer.phar"
    mkdir -p "$HOME/bin"
    curl -sS https://getcomposer.org/installer | "$PHP_BIN" -- --install-dir="$HOME/bin" --filename=composer.phar
    COMPOSER_BIN="$HOME/bin/composer.phar"
fi

# alt-php CLIs on CloudLinux can ship with phar disabled; Composer needs it.
PHP_PHAR_ARGS=()
if ! "$PHP_BIN" -m | grep -qi '^phar$'; then
    PHP_PHAR_ARGS=(-d extension=phar.so)
fi

composer() {
    # /opt/cpanel/composer/bin/composer is a shell wrapper; phars must run through our PHP.
    if head -c 2 "$COMPOSER_BIN" | grep -q '#!' && ! head -n 1 "$COMPOSER_BIN" | grep -q php; then
        "$COMPOSER_BIN" "$@"
    else
        "$PHP_BIN" ${PHP_PHAR_ARGS[@]+"${PHP_PHAR_ARGS[@]}"} "$COMPOSER_BIN" "$@"
    fi
}
artisan() { "$PHP_BIN" "$APP_DIR/artisan" "$@"; }

# ---------------------------------------------------------------------------
if [[ "${1:-}" != "--no-pull" ]]; then
    step "Pulling latest code from origin/$BRANCH"
    git fetch origin "$BRANCH"
    git checkout "$BRANCH"
    git pull --ff-only origin "$BRANCH"
fi
git log -1 --format='Deploying %h  %s  (%an, %ar)'

# ---------------------------------------------------------------------------
FIRST_DEPLOY=0
if [[ ! -f .env ]]; then
    FIRST_DEPLOY=1
    step "Creating production .env (first deploy)"
    cp .env.example .env
    set_env() {
        local key="$1" value="$2"
        if grep -qE "^#? ?${key}=" .env; then
            sed -i -E "s|^#? ?${key}=.*|${key}=${value}|" .env
        else
            printf '%s=%s\n' "$key" "$value" >> .env
        fi
    }
    set_env APP_NAME '"MedzSolutions"'
    set_env APP_ENV production
    set_env APP_DEBUG false
    set_env APP_URL "${APP_URL:-$APP_URL_DEFAULT}"
    set_env LOG_LEVEL error
    set_env QUEUE_CONNECTION sync
    if [[ -n "${DB_DATABASE:-}" ]]; then
        set_env DB_CONNECTION mysql
        set_env DB_HOST localhost
        set_env DB_PORT 3306
        set_env DB_DATABASE "$DB_DATABASE"
        set_env DB_USERNAME "${DB_USERNAME:-}"
        set_env DB_PASSWORD "\"${DB_PASSWORD:-}\""
    fi
    chmod 600 .env
fi

if grep -qE '^DB_CONNECTION=sqlite' .env && [[ ! -f database/database.sqlite ]]; then
    touch database/database.sqlite
fi

# ---------------------------------------------------------------------------
step "Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress

if ! grep -qE '^APP_KEY=base64:' .env; then
    step "Generating APP_KEY"
    artisan key:generate --force
fi

[[ -f public/build/manifest.json ]] \
    || fail "public/build/manifest.json is missing. Run 'npm run build' locally and push (use ./push.sh)."

# ---------------------------------------------------------------------------
step "Running database migrations"
artisan migrate --force

# ---------------------------------------------------------------------------
step "Publishing public/ to $PUBLIC_HTML"
mkdir -p "$PUBLIC_HTML"

# Fresh asset build each deploy; leave unrelated folders (cgi-bin, estatecamp, ...) untouched.
rm -rf "$PUBLIC_HTML/build"
(cd public && find . -mindepth 1 -maxdepth 1 \
    ! -name index.php ! -name .htaccess ! -name storage ! -name hot \
    -exec cp -R {} "$PUBLIC_HTML/" \;)

# index.php that boots the app from this checkout.
cat > "$PUBLIC_HTML/index.php" <<PHP
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

\$appPath = '$APP_DIR';

if (file_exists(\$maintenance = \$appPath.'/storage/framework/maintenance.php')) {
    require \$maintenance;
}

require \$appPath.'/vendor/autoload.php';

/** @var Application \$app */
\$app = require_once \$appPath.'/bootstrap/app.php';

\$app->usePublicPath(__DIR__);

\$app->handleRequest(Request::capture());
PHP

# .htaccess: PHP 8.3 handler + Laravel rewrites (replaces any old Node/Passenger mapping).
if [[ -f "$PUBLIC_HTML/.htaccess" ]] && ! grep -q 'MEDZ DEPLOY' "$PUBLIC_HTML/.htaccess"; then
    cp "$PUBLIC_HTML/.htaccess" "$PUBLIC_HTML/.htaccess.bak-$(date +%Y%m%d%H%M%S)"
fi
{
    echo "# MEDZ DEPLOY - generated by deploy.sh, edit public/.htaccess in the repo instead."
    echo "<IfModule mime_module>"
    echo "  AddHandler $PHP_HANDLER .php .php8 .phtml"
    echo "</IfModule>"
    echo
    cat public/.htaccess
} > "$PUBLIC_HTML/.htaccess"

# public storage link (uploaded post images).
if [[ ! -L "$PUBLIC_HTML/storage" ]]; then
    rm -rf "$PUBLIC_HTML/storage"
    ln -s "$APP_DIR/storage/app/public" "$PUBLIC_HTML/storage"
fi

# ---------------------------------------------------------------------------
step "Optimizing"
chmod -R ug+rwX storage bootstrap/cache
artisan optimize:clear
artisan optimize

step "Deployment complete"
if [[ $FIRST_DEPLOY -eq 1 ]]; then
    echo "First deploy: review $APP_DIR/.env (mail settings, etc.) and re-run 'php artisan optimize' after editing."
fi
