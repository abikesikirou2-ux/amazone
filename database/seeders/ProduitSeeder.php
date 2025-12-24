<?php

namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\ProduitImage;
use App\Models\ProduitVariante;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        $catMode = Categorie::where('nom', 'Mode & accessoires')->first();
        $catElec = Categorie::where('nom', 'Électronique & high‑tech')->first();
        $catMaison = Categorie::where('nom', 'Maison & décoration')->first();
        $catBeaute = Categorie::where('nom', 'Beauté & santé')->first();
        $catAlim = Categorie::where('nom', 'Alimentation & boissons')->first();
        $catSport = Categorie::where('nom', 'Sport & loisirs')->first();
        $catAuto = Categorie::where('nom', 'Automobile & bricolage')->first();

        // Nettoyer le dossier d'images produits (optionnel)
        Storage::disk('public')->makeDirectory('produits');

        $catalogue = [
            'mode' => [
                'categorie' => $catMode,
                'items' => [
                    'T‑shirts' => 'tshirt',
                    'Jeans' => 'jeans',
                    'Vestes en cuir' => 'leather jacket',
                    'Robes de soirée' => 'evening dress',
                    'Chaussures de sport' => 'sneakers',
                    'Bottes' => 'boots',
                    'Sacs à main' => 'handbag',
                    'Ceintures' => 'belt',
                    'Lunettes de soleil' => 'sunglasses',
                    'Montres' => 'watch',
                    'Bijoux fantaisie' => 'jewelry',
                    'Écharpes' => 'scarf',
                ],
                'segments' => ['femme','homme','enfant']
            ],
            'electronique' => [
                'categorie' => $catElec,
                'items' => [
                    'Smartphones' => 'smartphone',
                    'Tablettes' => 'tablet',
                    'Ordinateurs portables' => 'laptop',
                    'Écrans PC' => 'monitor',
                    'Télévisions' => 'tv screen',
                    'Casques audio' => 'headphones',
                    'Enceintes Bluetooth' => 'bluetooth speaker',
                    'Appareils photo numériques' => 'camera',
                    'Drones' => 'drone',
                    'Consoles de jeux' => 'game console',
                    'Chargeurs portables' => 'power bank',
                    'Claviers & souris' => 'keyboard mouse',
                ]
            ],
            'maison' => [
                'categorie' => $catMaison,
                'items' => [
                    'Canapés' => 'sofa',
                    'Tables basses' => 'coffee table',
                    'Chaises design' => 'design chair',
                    'Lampes de chevet' => 'bedside lamp',
                    'Rideaux' => 'curtains',
                    'Tapis' => 'rug',
                    'Linge de lit' => 'bedding',
                    'Vaisselle' => 'tableware',
                    'Bibliothèques' => 'bookshelf',
                    'Miroirs décoratifs' => 'decorative mirror',
                    'Plantes artificielles' => 'artificial plant',
                    'Horloges murales' => 'wall clock',
                ]
            ],
            'beaute' => [
                'categorie' => $catBeaute,
                'items' => [
                    'Crèmes hydratantes' => 'moisturizer',
                    'Sérums visage' => 'face serum',
                    'Fonds de teint' => 'foundation makeup',
                    'Rouges à lèvres' => 'lipstick',
                    'Mascaras' => 'mascara',
                    'Parfums' => 'perfume',
                    'Shampoings' => 'shampoo',
                    'Soins capillaires' => 'hair care',
                    'Brosses à cheveux' => 'hair brush',
                    'Compléments alimentaires' => 'supplement',
                    'Huiles essentielles' => 'essential oil',
                    'Produits de manucure' => 'manicure',
                ]
            ],
            'alimentation' => [
                'categorie' => $catAlim,
                'items' => [
                    'Fruits frais' => 'fresh fruits',
                    'Légumes bio' => 'organic vegetables',
                    'Pâtes' => 'pasta',
                    'Riz' => 'rice',
                    'Biscuits' => 'biscuits',
                    'Chocolats' => 'chocolate',
                    'Café' => 'coffee',
                    'Thés' => 'tea',
                    'Jus de fruits' => 'juice',
                    'Vins rouges' => 'red wine',
                    'Bières artisanales' => 'craft beer',
                    'Huiles d’olive' => 'olive oil',
                ]
            ],
            'sport' => [
                'categorie' => $catSport,
                'items' => [
                    'Ballons de football' => 'soccer ball',
                    'Raquettes de tennis' => 'tennis racket',
                    'Vélos' => 'bicycle',
                    'Tapis de yoga' => 'yoga mat',
                    'Haltères' => 'dumbbells',
                    'Gants de boxe' => 'boxing gloves',
                    'Skateboards' => 'skateboard',
                    'Jeux de société' => 'board game',
                    'Puzzles' => 'puzzle',
                    'Jouets éducatifs' => 'educational toy',
                    'Instruments de musique (guitares, claviers)' => 'guitar keyboard',
                    'Jeux vidéo' => 'video game',
                ]
            ],
            'auto' => [
                'categorie' => $catAuto,
                'items' => [
                    'Pneus' => 'car tire',
                    'Batteries de voiture' => 'car battery',
                    'Huiles moteur' => 'motor oil',
                    'Kits d’outillage' => 'tool kit',
                    'Perceuses électriques' => 'electric drill',
                    'Marteaux' => 'hammer',
                    'Peintures murales' => 'wall paint',
                    'Échelles' => 'ladder',
                    'Tondeuses à gazon' => 'lawn mower',
                    'Gants de travail' => 'work gloves',
                    'Pièces détachées auto' => 'car parts',
                    'Nettoyeurs haute pression' => 'pressure washer',
                ]
            ],
        ];

        foreach ($catalogue as $cle => $groupe) {
            $categorie = $groupe['categorie'];
            if (!$categorie) continue;
            foreach ($groupe['items'] as $nom => $query) {
                $basePrix = $this->prixAleatoirePour($cle);
                $slugItem = Str::slug($nom);

                // Pour la mode: créer trois segments (F/H/Enfant)
                $segments = ($cle === 'mode') ? ($groupe['segments'] ?? ['femme','homme','enfant']) : [null];
                foreach ($segments as $segment) {
                    $produit = Produit::create([
                        'nom' => $nom . ($segment ? ' - ' . ucfirst($segment) : ''),
                        'description' => $this->descriptionPour($cle, $nom),
                        'prix' => $basePrix,
                        'image' => null, // sera défini après téléchargement
                        'categorie_id' => $categorie->id,
                        'segment' => $segment,
                        'stock' => rand(10, 100),
                        'actif' => true,
                    ]);

                    // Images: télécharger 3 variantes
                    $catSlug = Str::slug($categorie->nom);
                    $sousDossier = $catSlug . '/' . $slugItem . ($segment ? '-' . $segment : '');
                    $this->creerImagesProduit($produit, $query, $sousDossier, $slugItem, $segment);

                    // Variantes de taille pour la mode uniquement
                    if ($cle === 'mode') {
                        foreach (['XS' => -2, 'S' => -1, 'M' => 0, 'L' => 2, 'XL' => 4] as $taille => $delta) {
                            ProduitVariante::create([
                                'produit_id' => $produit->id,
                                'taille' => $taille,
                                'prix' => max(1, $basePrix + $delta),
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function creerImagesProduit(Produit $produit, string $requete, string $sousDossier, string $slugItem, ?string $segment): void
    {
        $public = Storage::disk('public');
        $public->makeDirectory('produits/' . $sousDossier);
        $onlyLocal = filter_var(env('SEED_ONLY_LOCAL_IMAGES', false), FILTER_VALIDATE_BOOL);

        // Préférence: utiliser des images déjà générées localement si présentes
        $principale = null;
        $catSlug = explode('/', $sousDossier)[0] ?? '';
        $genCatMap = [
            'mode-et-accessoires' => 'fashion-accessories',
            'electronique-high-tech' => 'electronics-high-tech',
            'maison-decoration' => 'home-decoration',
            'beaute-sante' => 'beauty-health',
            'alimentation-boissons' => 'food-drinks',
            'sport-loisirs' => 'sports-leisure',
            'automobile-bricolage' => 'automotive-diy',
        ];
        $genCat = $genCatMap[$catSlug] ?? null;

        for ($i = 1; $i <= 3; $i++) {
            // Candidates: (1) generated naming <slugItem>-<segment>-N.png, (2) <slugItem>-N.png, (3) existing local i.jpg/png
            $candidates = [];
            $prodSlugSegment = $slugItem . ($segment ? '-' . $segment : '');
            // Current category folder
            $candidates[] = 'produits/' . $catSlug . '/' . $prodSlugSegment . '/' . $prodSlugSegment . '-' . $i . '.png';
            $candidates[] = 'produits/' . $catSlug . '/' . $slugItem . '/' . $slugItem . '-' . $i . '.png';
            $candidates[] = 'produits/' . $sousDossier . '/' . $i . '.png';
            $candidates[] = 'produits/' . $sousDossier . '/' . $i . '.jpg';
            // English prompt category folder (from generation tool)
            if ($genCat) {
                $candidates[] = 'produits/' . $genCat . '/' . $prodSlugSegment . '/' . $prodSlugSegment . '-' . $i . '.png';
                $candidates[] = 'produits/' . $genCat . '/' . $slugItem . '/' . $slugItem . '-' . $i . '.png';
            }

            $foundPath = null;
            foreach ($candidates as $cand) {
                if ($public->exists($cand)) {
                    $foundPath = $cand;
                    break;
                }
            }

            if (!$foundPath) {
                // Fallback: strict local mode ou téléchargement contrôlé
                if ($onlyLocal) {
                    // Image de secours locale (PNG 1x1 gris)
                    $foundPath = 'produits/' . $sousDossier . '/' . $i . '.png';
                    $contenu = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==');
                    $public->put($foundPath, $contenu);
                } else {
                    // Essayer une source publique, puis basculer sur un placeholder
                    $ext = 'jpg';
                    $url = 'https://loremflickr.com/800/600/' . urlencode($requete) . '?random=' . uniqid();
                    $contenu = @file_get_contents($url);
                    if ($contenu === false) {
                        $url2 = 'https://placehold.co/800x600?text=' . urlencode($produit->nom . ' ' . $i);
                        $contenu = @file_get_contents($url2);
                    }
                    if ($contenu === false) {
                        // Offline-safe fallback: tiny gray PNG (1x1)
                        $ext = 'png';
                        $contenu = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==');
                    }

                    $foundPath = 'produits/' . $sousDossier . '/' . $i . '.' . $ext;
                    $public->put($foundPath, $contenu);
                }
            }

            ProduitImage::create([
                'produit_id' => $produit->id,
                'path' => $foundPath,
                'ordre' => $i - 1,
            ]);

            if ($i === 1) {
                $principale = $foundPath;
            }
        }

        if ($principale) {
            $produit->image = $principale;
            $produit->save();
        }
    }

    private function prixAleatoirePour(string $cle): float
    {
        return match ($cle) {
            'mode' => (float)rand(15, 150),
            'electronique' => (float)rand(20, 2500),
            'maison' => (float)rand(10, 1200),
            'beaute' => (float)rand(5, 200),
            'alimentation' => (float)rand(2, 80),
            'sport' => (float)rand(8, 800),
            'auto' => (float)rand(6, 1200),
            default => (float)rand(5, 500),
        };
    }

    private function descriptionPour(string $cle, string $nom): string
    {
        return $nom . ' — Qualité sélectionnée, livraison rapide, satisfait ou remboursé.';
    }
}
