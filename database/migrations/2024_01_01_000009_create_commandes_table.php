<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande', 50)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->decimal('sous_total', 10, 2);
            $table->decimal('prix_livraison', 10, 2)->default(0);
            $table->decimal('reduction', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('statut', [
                'en_attente',
                'confirmee',
                'en_preparation',
                'expediee',
                'en_cours_livraison',
                'livree',
                'annulee',
                'en_attente_livreur'
            ])->default('en_attente');
            $table->foreignId('mode_livraison_id')->constrained('modes_livraison')->onDelete('restrict');
            $table->text('adresse_livraison');
            $table->string('ville_livraison')->nullable();
            $table->string('quartier_livraison')->nullable();
            $table->foreignId('point_relais_id')->nullable()->constrained('points_relais')->onDelete('set null');
            $table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('set null');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->string('numero_suivi', 100)->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'statut']);
            $table->index('numero_commande');
        });

        Schema::create('articles_commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('restrict');
            $table->foreignId('variante_id')->nullable()->constrained('produit_variantes')->nullOnDelete();
            $table->string('taille', 20)->nullable();
            $table->integer('quantite');
            $table->decimal('prix', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_commande');
        Schema::dropIfExists('commandes');
    }
};
