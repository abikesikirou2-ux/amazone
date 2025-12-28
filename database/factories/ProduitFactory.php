<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    protected $model = \App\Models\Produit::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'prix' => fake()->numberBetween(100, 20000),
            'stock' => fake()->numberBetween(0, 100),
            'actif' => true,
            // Assurer une categorie valide pour les tests
            'categorie_id' => function () {
                return \App\Models\Categorie::create(['nom' => fake()->word()])->id;
            },
        ];
    }
}
