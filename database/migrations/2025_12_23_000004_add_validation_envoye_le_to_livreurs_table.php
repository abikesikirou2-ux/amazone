<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livreurs', function (Blueprint $table) {
            $table->timestamp('validation_envoye_le')->nullable()->after('refuse_le');
        });
    }

    public function down(): void
    {
        Schema::table('livreurs', function (Blueprint $table) {
            $table->dropColumn('validation_envoye_le');
        });
    }
};
