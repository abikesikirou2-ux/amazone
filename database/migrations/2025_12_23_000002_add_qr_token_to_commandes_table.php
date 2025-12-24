<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('numero_suivi');
            $table->timestamp('recu_le')->nullable()->after('qr_token');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'recu_le']);
        });
    }
};
