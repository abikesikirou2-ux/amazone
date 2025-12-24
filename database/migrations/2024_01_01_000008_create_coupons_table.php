<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', ['pourcentage', 'montant_fixe']);
            $table->decimal('valeur', 10, 2);
            $table->decimal('montant_minimum', 10, 2)->default(0);
            $table->boolean('livraison_gratuite')->default(false);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('utilisations_max')->nullable();
            $table->integer('compteur_utilisation')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['code', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
