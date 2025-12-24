<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement']);
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('date_creation')->useCurrent();
            
            $table->index(['produit_id', 'date_creation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
