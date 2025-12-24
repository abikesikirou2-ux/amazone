<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->integer('note')->check('note >= 1 AND note <= 5');
            $table->text('commentaire')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'produit_id', 'commande_id']);
            $table->index('produit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
