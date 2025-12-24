# Génération d'images produits (studio)

Ce dossier contient un script pour générer des visuels produits en style studio (fond blanc/neutre, éclairage pro, centré) à partir d'une liste de prompts. Les images sont enregistrées dans `storage/app/public/produits/<categorie>/<slug>`. Assurez-vous d'avoir créé le lien public via `php artisan storage:link`.

## Option A — API OpenAI (recommandé, simple)

Prérequis:
- Python 3.9+
- `pip install -r requirements.txt`
- Variable d'environnement `OPENAI_API_KEY`

Installation et exécution:
```bash
cd scripts/imagegen
pip install -r requirements.txt
set OPENAI_API_KEY=sk-...   # Windows PowerShell: $env:OPENAI_API_KEY="sk-..."
python generate_openai.py --out ../../storage/app/public/produits --size 1024 --per-item 1
```

Paramètres utiles:
- `--category` pour limiter à une catégorie
- `--per-item` pour générer plusieurs variantes par produit (ex: 3)
- `--size` : 512, 768, 1024 (carré)

## Option B — Diffusers local (avancé)

Si vous préférez générer localement (sans API), utilisez Diffusers avec un modèle SDXL. Cela requiert Python scientifique (Torch) et peut être lent sur CPU.

Étapes (indicatives):
```bash
pip install diffusers transformers accelerate safetensors
# Exemple: SDXL base
# Voir: https://huggingface.co/stabilityai/stable-diffusion-xl-base-1.0
```

Vous devez ensuite adapter un script Diffusers pour boucler sur `prompts.json` et enregistrer les images.

## Notes anti‑copyright
- Prompts explicitement « unbranded / no logo / no watermark / no text ».
- Évitez toute mention de marques.

## Intégration Laravel
Les images sont déposées sous `storage/app/public/produits/...`. Servez-les via `public/storage` après:
```bash
php artisan storage:link -n
```
