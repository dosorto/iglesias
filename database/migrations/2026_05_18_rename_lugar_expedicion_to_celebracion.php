<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            // Agregar nueva columna si no existe
            if (!Schema::hasColumn('bautismos', 'lugar_celebracion')) {
                $table->string('lugar_celebracion')->nullable()->after('lugar_nacimiento');
            }
            
            // Asegurar que ministro_celebrante existe
            if (!Schema::hasColumn('bautismos', 'ministro_celebrante')) {
                $table->string('ministro_celebrante')->nullable();
            }
        });

        // Copiar datos de lugar_expedicion a lugar_celebracion si existen ambas columnas
        if (Schema::hasColumn('bautismos', 'lugar_expedicion')) {
            DB::statement('UPDATE bautismos SET lugar_celebracion = lugar_expedicion WHERE lugar_expedicion IS NOT NULL');

            Schema::table('bautismos', function (Blueprint $table) {
                $table->dropColumn('lugar_expedicion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            // Agregar nueva columna si no existe
            if (!Schema::hasColumn('bautismos', 'lugar_expedicion')) {
                $table->string('lugar_expedicion')->nullable()->after('lugar_nacimiento');
            }
        });

        // Copiar datos de vuelta
        DB::statement('UPDATE bautismos SET lugar_expedicion = lugar_celebracion WHERE lugar_celebracion IS NOT NULL');

        Schema::table('bautismos', function (Blueprint $table) {
            // Eliminar columna nueva
            if (Schema::hasColumn('bautismos', 'lugar_celebracion')) {
                $table->dropColumn('lugar_celebracion');
            }
        });
    }
};
