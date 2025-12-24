<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        return; // Migration désactivée: doublon avec add_segment_to_produits_table
    }

    public function down(): void
    {
        // no-op
    }
};
