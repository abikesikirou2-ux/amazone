#!/usr/bin/env sh
set -e

# Si vendor absent, installer
if [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader --no-interaction
fi

# Vérifier APP_KEY
if [ -z "${APP_KEY}" ]; then
  echo "WARNING: APP_KEY not set. Generate one locally with 'php artisan key:generate --show' and add to Railway env vars."
fi

# Optionnel: exécuter les migrations si RUN_MIGRATIONS=true
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
fi

# Lier le storage (idempotent)
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# Caches
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Port par défaut si Railway ne fournit pas $PORT
PORT=${PORT:-8000}

# Lancer le serveur intégré PHP (simple, adapté aux petits déploiements)
exec php -S 0.0.0.0:"${PORT}" -t public
