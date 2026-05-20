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
        $hasLugarCelebracion = Schema::hasColumn('bautismos', 'lugar_celebracion');
        $hasLugarExpedicion = Schema::hasColumn('bautismos', 'lugar_expedicion');
        $hasMinistroCelebrante = Schema::hasColumn('bautismos', 'ministro_celebrante');

        Schema::table('bautismos', function (Blueprint $table) use ($hasLugarCelebracion, $hasMinistroCelebrante) {
            if (! $hasLugarCelebracion) {
                $table->string('lugar_celebracion')->nullable()->after('lugar_nacimiento');
            }

            if (! $hasMinistroCelebrante) {
                $table->string('ministro_celebrante')->nullable();
            }
        });

        if ($hasLugarExpedicion) {
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
        $hasLugarExpedicion = Schema::hasColumn('bautismos', 'lugar_expedicion');
        $hasLugarCelebracion = Schema::hasColumn('bautismos', 'lugar_celebracion');

        Schema::table('bautismos', function (Blueprint $table) use ($hasLugarExpedicion) {
            if (! $hasLugarExpedicion) {
                $table->string('lugar_expedicion')->nullable()->after('lugar_nacimiento');
            }
        });

        if ($hasLugarCelebracion) {
            DB::statement('UPDATE bautismos SET lugar_expedicion = lugar_celebracion WHERE lugar_celebracion IS NOT NULL');
        }

        Schema::table('bautismos', function (Blueprint $table) use ($hasLugarCelebracion) {
            if ($hasLugarCelebracion) {
                $table->dropColumn('lugar_celebracion');
            }
        });
    }
};
