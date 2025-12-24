<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->decimal('livreur_lat', 10, 7)->nullable()->after('livreur_id');
            $table->decimal('livreur_lng', 10, 7)->nullable()->after('livreur_lat');
            $table->timestamp('livreur_last_seen_at')->nullable()->after('livreur_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['livreur_lat', 'livreur_lng', 'livreur_last_seen_at']);
        });
    }
};
