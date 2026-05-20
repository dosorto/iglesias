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
        Schema::table('bautismos', function (Blueprint $table) {
            // Make ministro_celebrante nullable
            $table->string('ministro_celebrante')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            // Revert to NOT NULL
            $table->string('ministro_celebrante')->nullable(false)->change();
        });
    }
};
