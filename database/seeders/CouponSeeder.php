<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $coupons = [
            [
                'code' => 'BIENVENUE10',
                'type' => 'pourcentage',
                'valeur' => 10,
                'montant_minimum' => 50,
                'livraison_gratuite' => false,
                'date_debut' => Carbon::now(),
                'date_fin' => Carbon::now()->addMonths(3),
                'utilisations_max' => 100,
                'compteur_utilisation' => 0,
                'actif' => true,
            ],
            [
                'code' => 'PROMO20',
                'type' => 'pourcentage',
                'valeur' => 20,
                'montant_minimum' => 100,
                'livraison_gratuite' => false,
                'date_debut' => Carbon::now(),
                'date_fin' => Carbon::now()->addMonth(),
                'utilisations_max' => 50,
                'compteur_utilisation' => 0,
                'actif' => true,
            ],
            [
                'code' => 'LIVRAISON',
                'type' => 'montant_fixe',
                'valeur' => 0,
                'montant_minimum' => 75,
                'livraison_gratuite' => true,
                'date_debut' => Carbon::now(),
                'date_fin' => Carbon::now()->addMonths(6),
                'utilisations_max' => null,
                'compteur_utilisation' => 0,
                'actif' => true,
            ],
            [
                'code' => 'SOLDES50',
                'type' => 'montant_fixe',
                'valeur' => 50,
                'montant_minimum' => 200,
                'livraison_gratuite' => false,
                'date_debut' => Carbon::now(),
                'date_fin' => Carbon::now()->addWeeks(2),
                'utilisations_max' => 30,
                'compteur_utilisation' => 0,
                'actif' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            // Idempotent: met à jour si le code existe déjà
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
