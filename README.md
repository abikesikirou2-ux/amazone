# mini-amazone

C'est une plateforme d'e-commerce basée sur Laravel.

## À propos
Ce dépôt contient l'application Mini Amazon (Laravel) pour la démonstration et le développement local.

## Usage rapide
- Admin préconfiguré: `miniamazone555@gmail.com` / `amazone@mini2025`
- Pour démarrer localement:
```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
npm run build
php artisan migrate --seed
php artisan serve
```

Pour les déploiements, configure les variables d'environnement et suis la documentation du projet.
