<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_relais', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal', 10);
            $table->string('telephone', 20)->nullable();
            $table->json('horaires_ouverture')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index('code_postal');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_relais');
    }
};
