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
        Schema::table('iglesias', function (Blueprint $table) {
            if (! Schema::hasColumn('iglesias', 'header_diocesis')) {
                $table->string('header_diocesis', 200)
                    ->nullable()
                    ->after('direccion');
            }

            if (! Schema::hasColumn('iglesias', 'header_lugar')) {
                $table->string('header_lugar', 255)
                    ->nullable()
                    ->after('header_diocesis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            if (Schema::hasColumn('iglesias', 'header_lugar')) {
                $table->dropColumn('header_lugar');
            }

            if (Schema::hasColumn('iglesias', 'header_diocesis')) {
                $table->dropColumn('header_diocesis');
            }
        });
    }
};
