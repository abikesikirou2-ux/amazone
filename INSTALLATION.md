# Mini Amazon - Plateforme E-commerce

Site e-commerce moderne et responsive développé avec Laravel 12, incluant un système complet de gestion de panier, commandes, livraisons avec points relais et livreurs, coupons de réduction et avis clients.

## 🚀 Fonctionnalités

### Pour les Clients
- ✅ Navigation par catégories et recherche de produits
- ✅ Système de panier dynamique
- ✅ Deux modes de livraison:
  - 🏠 Livraison à domicile avec assignation automatique de livreur
  - 📍 Point relais (recherche par code postal)
- ✅ Système de coupons de réduction
- ✅ Suivi des commandes en temps réel
- ✅ Avis et notes sur les produits
- ✅ Interface responsive et moderne

### Pour les Livreurs
- ✅ Enregistrement par ville et quartier
- ✅ Notifications email automatiques lors de nouvelles commandes
- ✅ Confirmation de disponibilité

### Pour les Administrateurs
- ✅ Gestion complète des produits
- ✅ Gestion des catégories
- ✅ Gestion des stocks avec historique
- ✅ Gestion des commandes
- ✅ Gestion des coupons promotionnels
- ✅ Gestion des livreurs et points relais

## 📋 Prérequis

- PHP >= 8.2
- Composer
- MySQL >= 8.0 (dev/prod)
- Node.js 18+ & NPM (pour Vite)

## 🛠️ Installation

### 1. Cloner le projet
```bash
git clone <url-du-repo>
cd mini_amazone
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l'environnement
```bash
copy .env.example .env
php artisan key:generate
```

### 3. Configuration de l'environnement
Assurez-vous que MySQL est démarré et que les paramètres correspondent:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_amazone
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Créer la base de données
```bash
# Avec MySQL CLI
mysql -u root -p
CREATE DATABASE mini_amazone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Ou utilisez phpMyAdmin pour créer une base nommée `mini_amazone`.

### 5. Exécuter les migrations
```bash
php artisan migrate
```

Cette commande créera toutes les tables nécessaires:
- users (avec rôles admin/client)
- categories
- produits
- paniers & articles_panier (avec variantes de taille)
- modes_livraison
- points_relais
- livreurs (avec ville/quartier)
- adresses_livraison (avec quartier)
- commandes (avec assignation livreur)
- articles_commande (avec variantes de taille)
- coupons
- avis
- mouvements_stock
- produit_images (galerie 3 images/produit)
- produit_variantes (tailles et prix par taille)

### 6. (Conseillé) Créer le lien de stockage public
Pour servir les images téléchargées par les seeders:
```bash
php artisan storage:link
```

### 7. Peupler la base avec des données de test
```bash
php artisan db:seed
```

Ceci créera:
- 1 compte admin (admin@miniamaz.cd / password)
- 1 compte client (client@test.cd / password)
- 5 clients supplémentaires
- 7 catégories de produits (selon votre liste)
- Des produits pour toutes les sous‑rubriques listées (avec 3 images chacun)
- Pour "Mode & accessoires": produits classés par segment Femme/Homme/Enfant et tailles (XS→XL) avec prix dynamiques
- 3 modes de livraison
- 4 points relais à Kinshasa
- 6 livreurs dans différents quartiers
- 4 coupons promotionnels actifs

### 8. Installer Laravel Breeze (Authentification, optionnel)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### 9. Démarrer le serveur

Mode tout‑en‑un (PHP + queue + Vite):
```bash
composer run dev
```

Ou séparément:
```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

Le site sera accessible à: http://localhost:8000

## 👥 Comptes de test

### Admin
- **Email:** admin@miniamaz.cd
- **Mot de passe:** password

### Client
- **Email:** client@test.cd
- **Mot de passe:** password

## 📦 Structure de la base de données

### Tables principales:
1. **users** - Utilisateurs (admin/client) avec système de rôles
2. **categories** - Catégories de produits
3. **produits** - Catalogue produits avec prix, stock, images
4. **paniers** - Paniers des utilisateurs
5. **articles_panier** - Ligne de panier
6. **modes_livraison** - Domicile, Point relais, Express
7. **points_relais** - Points de retrait avec géolocalisation
8. **livreurs** - Livreurs avec ville et quartier
9. **adresses_livraison** - Adresses clients avec quartier
10. **commandes** - Commandes avec livreur assigné
11. **articles_commande** - Lignes de commande
12. **coupons** - Codes promo (fixe/pourcentage)
13. **avis** - Notes et commentaires sur produits
14. **mouvements_stock** - Historique des stocks

### Relations clés:
- User → Panier (1:1)
- User → Commandes (1:N)
- Produit → Catégorie (N:1)
- Commande → Livreur (N:1) - Assignation automatique par ville/quartier
- Commande → ModeLivraison (N:1)
- Commande → PointRelais (N:1) - Si livraison en point relais

## 🎨 Technologies utilisées

- **Backend:** Laravel 12
- **Frontend:** Blade + Tailwind (via Vite)
- **Bundler:** Vite
- **Base de données:** MySQL 8 (tests: SQLite mémoire)
- **Authentification:** Laravel Breeze (optionnel)
- **Icons:** Font Awesome 6
- **Interactivité:** Alpine.js

## 🚚 Système de livraison

### Livraison à domicile
1. Client saisit son adresse avec ville et quartier
2. Système recherche un livreur disponible dans la même ville/quartier
3. Livreur reçoit notification email automatique
4. Livreur confirme sa disponibilité
5. Commande passe en "en_attente_livreur" puis "en_preparation"

### Point relais
1. Client saisit son code postal
2. Système affiche les points relais disponibles
3. Client choisit son point relais
4. Commande prête pour retrait

## 💳 Coupons disponibles

| Code | Type | Valeur | Conditions |
|------|------|--------|------------|
| BIENVENUE10 | Pourcentage | 10% | Min. 50$ |
| PROMO20 | Pourcentage | 20% | Min. 100$ |
| LIVRAISON | Livraison gratuite | - | Min. 75$ |
| SOLDES50 | Fixe | 50$ | Min. 200$ |

## 📱 Pages disponibles

### Public
- `/` - Accueil avec produits vedette
- `/produits` - Catalogue avec filtres et tri
- `/produit/{id}` - Détails produit avec avis
- `/recherche?q=...` - Recherche produits

## 🖼️ Images produits

Les seeders ajoutent 3 images par produit et privilégient les images locales générées (dans `storage/app/public/produits`). Si aucune image locale n'est trouvée:
- Par défaut: tentative de téléchargement depuis une source publique, puis placeholder en secours.
- Mode strict local (sans téléchargement): définissez `SEED_ONLY_LOCAL_IMAGES=true` dans `.env` pour ne créer que des placeholders locaux si aucune image n'est présente.

Assurez‑vous d'avoir exécuté `php artisan storage:link` pour servir les fichiers depuis `public/storage`.

### Authentifié
- `/panier` - Panier d'achats
- `/commande/creer` - Tunnel de commande
- `/commandes` - Historique des commandes
- `/commande/{id}` - Détails d'une commande
- `/compte` - Profil utilisateur

## 🧪 Tests

Les tests utilisent SQLite en mémoire (voir `phpunit.xml`).

Exécuter la suite:
```bash
composer test
# ou
php artisan test
# ou
vendor\bin\phpunit
```

En cas d'erreur de table manquante en Feature tests, assurez‑vous d'utiliser le trait `RefreshDatabase`.

## 🔧 Dépannage

- Échec `php artisan db:seed` ou `migrate:fresh --seed` (Exit Code: 1):
  - Vérifiez `.env` (connexion MySQL ou chemin SQLite), puis:
  ```bash
  php artisan optimize:clear
  php artisan migrate:fresh --seed
  ```
- Erreur « no such table: categories » en visitant `/` (tests):
  - Les migrations ne sont pas chargées; utilisez `RefreshDatabase` dans vos tests Feature.
- Images non servies:
  - Créez le lien: `php artisan storage:link -n` et vérifiez que les fichiers sont sous `public/storage`.
- Conflit de migration (colonnes en double):
  - Neutralisez la migration en double, puis relancez `php artisan migrate:fresh --seed`.

## 🎯 Fonctionnalités à venir (optionnel)

- [ ] Panel admin complet
- [ ] Paiement en ligne (Stripe/PayPal)
- [ ] Emails transactionnels complets
- [ ] Tableau de bord livreur
- [ ] Tracking GPS des livraisons
- [ ] Chat support client
- [ ] Programme de fidélité
- [ ] Wishlist produits

## 📄 License

Ce projet est un projet éducatif développé pour démonstration.

## 👨‍💻 Auteur

Projet Mini Amazon - Plateforme E-commerce complète

---

**Note importante:** Avant de lancer le serveur, assurez-vous que:
1. ✅ MySQL est démarré
2. ✅ La base de données `mini_amazone` existe
3. ✅ Les migrations sont exécutées: `php artisan migrate`
4. ✅ Les seeders sont exécutés: `php artisan db:seed`
5. ✅ Laravel Breeze est installé: `php artisan breeze:install blade`