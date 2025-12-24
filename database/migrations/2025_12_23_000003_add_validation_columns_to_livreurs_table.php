<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livreurs', function (Blueprint $table) {
            $table->boolean('valide')->default(false)->after('disponible');
            $table->timestamp('valide_le')->nullable()->after('valide');
            $table->string('validation_token', 100)->nullable()->unique()->after('valide_le');
            $table->timestamp('refuse_le')->nullable()->after('validation_token');
        });
    }

    public function down(): void
    {
        Schema::table('livreurs', function (Blueprint $table) {
            $table->dropColumn(['valide', 'valide_le', 'validation_token', 'refuse_le']);
        });
    }
};
