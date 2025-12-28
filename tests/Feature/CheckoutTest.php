<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Produit;
use App\Models\Panier;
use App\Models\ArticlePanier;
use App\Models\ModeLivraison;
use App\Models\Livreur;
use App\Models\Coupon;
use App\Models\Commande;
use App\Models\MouvementStock;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_and_generates_pdf_and_decrements_stock(): void
    {
        Storage::fake('local');
        Mail::fake();

        $user = User::factory()->create();

        $produit = Produit::factory()->create(['stock' => 10, 'prix' => 1000]);

        // Créer panier et article
        $panier = Panier::create(['user_id' => $user->id]);
        ArticlePanier::create([
            'panier_id' => $panier->id,
            'produit_id' => $produit->id,
            'quantite' => 2,
            'prix' => 1000,
        ]);

        $mode = ModeLivraison::create(['nom' => 'Standard', 'prix' => 500, 'actif' => true, 'jours_estimes' => 3]);

        $response = $this->actingAs($user)->post('/commande', [
            'mode_livraison_id' => $mode->id,
            'type_livraison' => 'domicile',
            'adresse_livraison' => '1 rue Test',
            'ville_livraison' => 'Testville',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertNull(session('error'), 'Unexpected session error: ' . session('error'));

        $commande = Commande::first();
        $this->assertNotNull($commande);

        // Vérifier stock et mouvement
        $this->assertDatabaseHas('produits', ['id' => $produit->id, 'stock' => 8]);
        $this->assertDatabaseHas('mouvements_stock', ['commande_id' => $commande->id, 'produit_id' => $produit->id]);

        // Simuler paiement
        $payResponse = $this->actingAs($user)->post("/commande/{$commande->id}/payer");
        $payResponse->assertStatus(200);

        $commande->refresh();
        $this->assertEquals('confirmee', $commande->statut);
        $this->assertNotNull($commande->recu_le);

        // Vérifier PDF enregistré
        Storage::disk('local')->assertExists('recu/recu_' . $commande->numero_commande . '.pdf');
    }

    public function test_checkout_applies_coupon_and_increments_usage(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $produit = Produit::factory()->create(['stock' => 5, 'prix' => 2000]);
        $panier = Panier::create(['user_id' => $user->id]);
        ArticlePanier::create([
            'panier_id' => $panier->id,
            'produit_id' => $produit->id,
            'quantite' => 1,
            'prix' => 2000,
        ]);

        $mode = ModeLivraison::create(['nom' => 'Standard', 'prix' => 0, 'actif' => true, 'jours_estimes' => 2]);

        $coupon = Coupon::create([
            'code' => 'TEST10',
            'type' => 'pourcentage',
            'valeur' => 10,
            'montant_minimum' => 0,
            'livraison_gratuite' => false,
            'date_debut' => now()->subDay(),
            'date_fin' => now()->addDay(),
            'utilisations_max' => 10,
            'compteur_utilisation' => 0,
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->post('/commande', [
            'mode_livraison_id' => $mode->id,
            'type_livraison' => 'domicile',
            'adresse_livraison' => '1 rue Test',
            'ville_livraison' => 'Testville',
            'coupon_code' => 'TEST10',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertNull(session('error'), 'Unexpected session error: ' . session('error'));

        $commande = Commande::first();
        $this->assertNotNull($commande);
        $this->assertEquals($coupon->id, $commande->coupon_id);

        // Vérifier que l'utilisation a été incrémentée
        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'compteur_utilisation' => 1]);
    }

    public function test_checkout_assigns_livreur_and_sends_notification(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $produit = Produit::factory()->create(['stock' => 3, 'prix' => 500]);
        $panier = Panier::create(['user_id' => $user->id]);
        ArticlePanier::create([
            'panier_id' => $panier->id,
            'produit_id' => $produit->id,
            'quantite' => 1,
            'prix' => 500,
        ]);

        $mode = ModeLivraison::create(['nom' => 'Domicile', 'prix' => 500, 'actif' => true, 'jours_estimes' => 3]);

        $livreur = Livreur::factory()->create(['ville' => 'Testville', 'disponible' => true]);

        $response = $this->actingAs($user)->post('/commande', [
            'mode_livraison_id' => $mode->id,
            'type_livraison' => 'domicile',
            'adresse_livraison' => 'Adresse 1',
            'ville_livraison' => 'Testville',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertNull(session('error'), 'Unexpected session error: ' . session('error'));

        $commande = Commande::first();
        $this->assertNotNull($commande);
        $this->assertNotNull($commande->livreur_id);

        // Vérifier qu'un e‑mail a été mis en file pour le livreur (DemandeDisponibiliteLivreur)
        Mail::assertQueued(\App\Mail\DemandeDisponibiliteLivreur::class, function ($mail) use ($livreur) {
            return $mail->hasTo($livreur->email);
        });
    }
}
