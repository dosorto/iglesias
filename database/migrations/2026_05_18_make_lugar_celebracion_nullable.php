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
            // Make lugar_celebracion nullable
            $table->string('lugar_celebracion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            // Revert to NOT NULL
            $table->string('lugar_celebracion')->nullable(false)->change();
        });
    }
};
