<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livreur>
 */
class LivreurFactory extends Factory
{
    protected $model = \App\Models\Livreur::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->phoneNumber(),
            'ville' => fake()->city(),
            'quartier' => fake()->streetName(),
            'password' => Hash::make('password'),
            'disponible' => true,
            'valide' => true,
            'validation_token' => Str::random(20),
            'remember_token' => Str::random(10),
        ];
    }
}
