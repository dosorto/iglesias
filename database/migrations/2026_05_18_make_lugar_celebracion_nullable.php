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
        if (! Schema::hasColumn('bautismos', 'lugar_celebracion')) {
            return;
        }

        Schema::table('bautismos', function (Blueprint $table) {
            $table->string('lugar_celebracion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('bautismos', 'lugar_celebracion')) {
            return;
        }

        Schema::table('bautismos', function (Blueprint $table) {
            $table->string('lugar_celebracion')->nullable(false)->change();
        });
    }
};
