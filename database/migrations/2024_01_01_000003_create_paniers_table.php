<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('articles_panier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panier_id')->constrained('paniers')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('variante_id')->nullable()->constrained('produit_variantes')->nullOnDelete();
            $table->string('taille', 20)->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix', 10, 2);
            $table->timestamps();
            
            $table->unique(['panier_id', 'produit_id', 'variante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_panier');
        Schema::dropIfExists('paniers');
    }
};
