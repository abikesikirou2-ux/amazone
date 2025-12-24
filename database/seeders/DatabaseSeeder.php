<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Livreur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer / mettre à jour uniquement l'admin (email déjà vérifié)
        User::updateOrCreate(
            ['email' => 'miniamazone555@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('amazone@mini2025'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Supprimer tous les autres utilisateurs (ne garder que l'admin)
        User::query()
            ->where('email', '!=', 'miniamazone555@gmail.com')
            ->delete();

        // Supprimer tous les comptes livreurs
        Livreur::query()->delete();

        // Lancer les autres seeders
        $this->call([
            CategorieSeeder::class,
            ProduitSeeder::class,
            ModeLivraisonSeeder::class,
            PointRelaisSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
