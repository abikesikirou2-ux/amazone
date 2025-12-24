<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gestion spécifique SQLite: recréer la table sans la colonne slug
        if (config('database.default') === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::create('categories_tmp', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Copier les données existantes (sans slug)
            \Illuminate\Support\Facades\DB::statement('INSERT INTO categories_tmp (id, nom, description, created_at, updated_at) SELECT id, nom, description, created_at, updated_at FROM categories');

            Schema::drop('categories');
            Schema::rename('categories_tmp', 'categories');

            Schema::enableForeignKeyConstraints();
        } else {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'slug')) {
                    $table->dropColumn('slug');
                }
            });
        }
    }

    public function down(): void
    {
        if (config('database.default') === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::create('categories_tmp', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->string('slug')->unique()->nullable();
                $table->timestamps();
            });

            \Illuminate\Support\Facades\DB::statement('INSERT INTO categories_tmp (id, nom, description, slug, created_at, updated_at) SELECT id, nom, description, NULL as slug, created_at, updated_at FROM categories');

            Schema::drop('categories');
            Schema::rename('categories_tmp', 'categories');

            Schema::enableForeignKeyConstraints();
        } else {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->unique()->nullable();
            });
        }
    }
};
