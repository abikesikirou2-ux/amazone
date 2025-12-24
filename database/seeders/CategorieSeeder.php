<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'nom' => 'Mode & accessoires',
                'description' => 'Vêtements, chaussures et accessoires pour femme, homme et enfant',
            ],
            [
                'nom' => 'Électronique & high‑tech',
                'description' => 'Smartphones, tablettes, ordinateurs et audio/vidéo',
            ],
            [
                'nom' => 'Maison & décoration',
                'description' => 'Meubles, déco, linge de maison et arts de la table',
            ],
            [
                'nom' => 'Beauté & santé',
                'description' => 'Soins visage, maquillage, parfums et bien‑être',
            ],
            [
                'nom' => 'Alimentation & boissons',
                'description' => 'Épicerie, produits frais et boissons',
            ],
            [
                'nom' => 'Sport & loisirs',
                'description' => 'Équipements sportifs, jeux et instruments',
            ],
            [
                'nom' => 'Automobile & bricolage',
                'description' => 'Entretien auto, outillage et jardin',
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}
