<?php

namespace Database\Seeders;

use App\Models\Livreur;
use Illuminate\Database\Seeder;

class LivreurSeeder extends Seeder
{
    public function run()
    {
        $livreurs = [
            [
                'nom' => 'Kabongo',
                'prenom' => 'Jean',
                'email' => 'jean.kabongo@livraison.cd',
                'telephone' => '+243 900 000 001',
                'ville' => 'Kinshasa',
                'quartier' => 'Gombe',
                'disponible' => true,
            ],
            [
                'nom' => 'Mbuyi',
                'prenom' => 'Marie',
                'email' => 'marie.mbuyi@livraison.cd',
                'telephone' => '+243 900 000 002',
                'ville' => 'Kinshasa',
                'quartier' => 'Lemba',
                'disponible' => true,
            ],
            [
                'nom' => 'Nkulu',
                'prenom' => 'Patrick',
                'email' => 'patrick.nkulu@livraison.cd',
                'telephone' => '+243 900 000 003',
                'ville' => 'Kinshasa',
                'quartier' => 'Matongé',
                'disponible' => true,
            ],
            [
                'nom' => 'Tshisekedi',
                'prenom' => 'Joseph',
                'email' => 'joseph.tshisekedi@livraison.cd',
                'telephone' => '+243 900 000 004',
                'ville' => 'Kinshasa',
                'quartier' => 'Ngaliema',
                'disponible' => true,
            ],
            [
                'nom' => 'Lukaku',
                'prenom' => 'David',
                'email' => 'david.lukaku@livraison.cd',
                'telephone' => '+243 900 000 005',
                'ville' => 'Kinshasa',
                'quartier' => 'Kalamu',
                'disponible' => true,
            ],
            [
                'nom' => 'Kasongo',
                'prenom' => 'Sarah',
                'email' => 'sarah.kasongo@livraison.cd',
                'telephone' => '+243 900 000 006',
                'ville' => 'Kinshasa',
                'quartier' => 'Kintambo',
                'disponible' => true,
            ],
        ];

        foreach ($livreurs as $livreur) {
            Livreur::create($livreur);
        }
    }
}
