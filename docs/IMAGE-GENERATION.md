# Génération d'images produits (studio)

Ce module génère des visuels produits de style studio (fond blanc/neutre, éclairage professionnel) pour le catalogue.

## Pré-requis
- Python 3.10+
- `pip`
- Clé API OpenAI exportée dans `OPENAI_API_KEY`

## Installation
```bash
cd tools/catalog_images
pip install -r requirements.txt
```

## Configuration
Le fichier `tools/catalog_images/prompts.json` contient:
- `style`: directives globales (fond, éclairage, composition)
- `categories`: liste des produits par catégorie (slugs coté app: produits/<categorie>/<produit>)

## Génération
```bash
# toutes les catégories
python tools/catalog_images/generate_images.py

# une catégorie seulement
python tools/catalog_images/generate_images.py --category fashion-accessories

# limiter le nombre de produits (ex: 3)
python tools/catalog_images/generate_images.py --limit 3
```

Images générées dans `storage/app/public/produits/<categorie>/<produit>/<produit>-{1..3}.png`.

Créez/validez le lien public:
```bash
php artisan storage:link -n
```

## Intégration
L'app charge les images depuis `public/storage`. Les seeders et modèles utilisent `asset('storage/...')`.

## Notes
- Prompts conçus pour un fond blanc/neutre, pas de watermark/logo.
- Coût API: chaque image a un coût. Pensez à limiter `--limit` pour des tests.
- Si une génération échoue, un PNG de secours 1x1 sera écrit pour garantir la cohérence des chemins.
