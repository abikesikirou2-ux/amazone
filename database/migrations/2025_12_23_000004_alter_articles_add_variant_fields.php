<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si les colonnes existent déjà (migrations de base modifiées), ne rien faire
        if (Schema::hasColumn('articles_panier', 'variante_id') && Schema::hasColumn('articles_panier', 'taille')
            && Schema::hasColumn('articles_commande', 'variante_id') && Schema::hasColumn('articles_commande', 'taille')) {
            return;
        }

        // Voie de secours pour anciens schémas
        if (!Schema::hasColumn('articles_panier', 'variante_id')) {
            Schema::table('articles_panier', function (Blueprint $table) {
                $table->foreignId('variante_id')->nullable()->after('produit_id')->constrained('produit_variantes')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('articles_panier', 'taille')) {
            Schema::table('articles_panier', function (Blueprint $table) {
                $table->string('taille', 20)->nullable()->after('variante_id');
            });
        }
        if (!Schema::hasColumn('articles_commande', 'variante_id')) {
            Schema::table('articles_commande', function (Blueprint $table) {
                $table->foreignId('variante_id')->nullable()->after('produit_id')->constrained('produit_variantes')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('articles_commande', 'taille')) {
            Schema::table('articles_commande', function (Blueprint $table) {
                $table->string('taille', 20)->nullable()->after('variante_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('articles_commande', 'variante_id')) {
            Schema::table('articles_commande', function (Blueprint $table) {
                $table->dropConstrainedForeignId('variante_id');
            });
        }
        if (Schema::hasColumn('articles_commande', 'taille')) {
            Schema::table('articles_commande', function (Blueprint $table) {
                $table->dropColumn('taille');
            });
        }
        if (Schema::hasColumn('articles_panier', 'variante_id')) {
            Schema::table('articles_panier', function (Blueprint $table) {
                $table->dropConstrainedForeignId('variante_id');
            });
        }
        if (Schema::hasColumn('articles_panier', 'taille')) {
            Schema::table('articles_panier', function (Blueprint $table) {
                $table->dropColumn('taille');
            });
        }
    }
};
