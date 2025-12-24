<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livreurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone', 20);
            $table->string('ville');
            $table->string('quartier');
            $table->boolean('disponible')->default(true);
            $table->timestamps();
            
            $table->index(['ville', 'quartier', 'disponible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livreurs');
    }
};
