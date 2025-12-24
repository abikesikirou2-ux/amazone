<?php

namespace Database\Seeders;

use App\Models\ModeLivraison;
use Illuminate\Database\Seeder;

class ModeLivraisonSeeder extends Seeder
{
    public function run()
    {
        $modes = [
            [
                'nom' => 'Livraison à domicile',
                'prix' => 5.99,
                'jours_estimes' => 3,
                'actif' => true,
                'description' => 'Livraison directement à votre adresse',
            ],
            [
                'nom' => 'Point Relais',
                'prix' => 3.99,
                'jours_estimes' => 4,
                'actif' => true,
                'description' => 'Retrait dans un point relais près de chez vous',
            ],
            [
                'nom' => 'Livraison Express',
                'prix' => 12.99,
                'jours_estimes' => 1,
                'actif' => true,
                'description' => 'Livraison en 24h',
            ],
        ];

        foreach ($modes as $mode) {
            ModeLivraison::create($mode);
        }
    }
}
