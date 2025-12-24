<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Livreur;

class AdminOnlySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_keeps_only_admin_user_and_removes_livreurs(): void
    {
        // Préparer quelques données parasites
        User::factory()->create(['email' => 'client@exemple.cd', 'role' => 'client']);
        Livreur::factory()->create(['email' => 'livreur@exemple.cd']);

        // Exécuter notre seeder
        $this->seed(DatabaseSeeder::class);

        // Vérifier qu'un seul utilisateur existe et que c'est l'admin
        $this->assertEquals(1, User::query()->count());
        $admin = User::query()->first();
        $this->assertNotNull($admin);
        $this->assertEquals('admin@miniamaz.cd', $admin->email);
        $this->assertEquals('admin', $admin->role);
        $this->assertNotNull($admin->email_verified_at);

        // Vérifier qu'aucun livreur n'existe
        $this->assertEquals(0, Livreur::query()->count());
    }
}
