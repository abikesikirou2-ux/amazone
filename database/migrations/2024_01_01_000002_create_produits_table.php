<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->string('image')->nullable();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('restrict');
            $table->integer('stock')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['actif', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
