<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adresses_livraison', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom_complet');
            $table->string('telephone', 20);
            $table->string('adresse');
            $table->string('ville');
            $table->string('quartier')->nullable();
            $table->string('code_postal', 10);
            $table->string('pays')->default('Congo');
            $table->boolean('par_defaut')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adresses_livraison');
    }
};
