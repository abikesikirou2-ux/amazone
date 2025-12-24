<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produit_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->string('taille', 20);
            $table->decimal('prix', 10, 2);
            $table->timestamps();

            $table->unique(['produit_id', 'taille']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produit_variantes');
    }
};
