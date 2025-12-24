<?php

namespace Database\Seeders;

use App\Models\PointRelais;
use Illuminate\Database\Seeder;

class PointRelaisSeeder extends Seeder
{
    public function run()
    {
        // Supprimer tous les points relais existants pour ne garder que ceux du Bénin
        PointRelais::query()->delete();

        $points = [
            [
                'nom' => 'Relais Cotonou Dantokpa',
                'adresse' => 'Marché Dantokpa, Cotonou',
                'ville' => 'Cotonou',
                'code_postal' => 'BJ-CTN',
                'telephone' => '+229 60 00 00 01',
                'horaires_ouverture' => json_encode([
                    'Lundi-Vendredi' => '8h-18h',
                    'Samedi' => '9h-17h',
                    'Dimanche' => 'Fermé'
                ]),
                'latitude' => 6.3703,
                'longitude' => 2.3912,
                'actif' => true,
            ],
            [
                'nom' => 'Relais Abomey-Calavi Centre',
                'adresse' => 'Route d’Abomey-Calavi, Centre-ville',
                'ville' => 'Abomey-Calavi',
                'code_postal' => 'BJ-ABC',
                'telephone' => '+229 60 00 00 02',
                'horaires_ouverture' => json_encode([
                    'Lundi-Vendredi' => '8h-18h',
                    'Samedi' => '9h-15h',
                    'Dimanche' => 'Fermé'
                ]),
                'latitude' => 6.4485,
                'longitude' => 2.3557,
                'actif' => true,
            ],
            [
                'nom' => 'Relais Porto-Novo Tokpota',
                'adresse' => 'Quartier Tokpota, Porto-Novo',
                'ville' => 'Porto-Novo',
                'code_postal' => 'BJ-PNV',
                'telephone' => '+229 60 00 00 03',
                'horaires_ouverture' => json_encode([
                    'Lundi-Samedi' => '8h-19h',
                    'Dimanche' => '10h-14h'
                ]),
                'latitude' => 6.4969,
                'longitude' => 2.6283,
                'actif' => true,
            ],
            [
                'nom' => 'Relais Parakou Guéma',
                'adresse' => 'Quartier Guéma, Parakou',
                'ville' => 'Parakou',
                'code_postal' => 'BJ-PRK',
                'telephone' => '+229 60 00 00 04',
                'horaires_ouverture' => json_encode([
                    'Lundi-Vendredi' => '9h-17h',
                    'Samedi' => '9h-13h',
                    'Dimanche' => 'Fermé'
                ]),
                'latitude' => 9.3372,
                'longitude' => 2.6303,
                'actif' => true,
            ],
            [
                'nom' => 'Relais Bohicon Carrefour',
                'adresse' => 'Carrefour central, Bohicon',
                'ville' => 'Bohicon',
                'code_postal' => 'BJ-BHC',
                'telephone' => '+229 60 00 00 05',
                'horaires_ouverture' => json_encode([
                    'Lundi-Vendredi' => '8h-18h',
                    'Samedi' => '9h-15h',
                    'Dimanche' => 'Fermé'
                ]),
                'latitude' => 7.1783,
                'longitude' => 2.0667,
                'actif' => true,
            ],
        ];

        foreach ($points as $point) {
            PointRelais::create($point);
        }
    }
}
