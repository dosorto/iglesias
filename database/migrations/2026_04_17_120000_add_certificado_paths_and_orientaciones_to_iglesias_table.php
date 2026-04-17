<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            if (! Schema::hasColumn('iglesias', 'path_certificado_confirmacion')) {
                $table->string('path_certificado_confirmacion')->nullable()->after('path_certificado_bautismo');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_primera_comunion')) {
                $table->string('path_certificado_primera_comunion')->nullable()->after('path_certificado_confirmacion');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_matrimonio')) {
                $table->string('path_certificado_matrimonio')->nullable()->after('path_certificado_primera_comunion');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_curso')) {
                $table->string('path_certificado_curso')->nullable()->after('path_certificado_matrimonio');
            }

            if (! Schema::hasColumn('iglesias', 'orientacion_certificado_bautismo')) {
                $table->string('orientacion_certificado_bautismo', 20)
                    ->default('portrait')
                    ->after('orientacion_certificado');
            }

            if (! Schema::hasColumn('iglesias', 'orientacion_certificado_confirmacion')) {
                $table->string('orientacion_certificado_confirmacion', 20)
                    ->default('portrait')
                    ->after('orientacion_certificado_bautismo');
            }

            if (! Schema::hasColumn('iglesias', 'orientacion_certificado_primera_comunion')) {
                $table->string('orientacion_certificado_primera_comunion', 20)
                    ->default('portrait')
                    ->after('orientacion_certificado_confirmacion');
            }

            if (! Schema::hasColumn('iglesias', 'orientacion_certificado_matrimonio')) {
                $table->string('orientacion_certificado_matrimonio', 20)
                    ->default('portrait')
                    ->after('orientacion_certificado_primera_comunion');
            }

            if (! Schema::hasColumn('iglesias', 'orientacion_certificado_curso')) {
                $table->string('orientacion_certificado_curso', 20)
                    ->default('landscape')
                    ->after('orientacion_certificado_matrimonio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            $table->dropColumn([
                'path_certificado_confirmacion',
                'path_certificado_primera_comunion',
                'path_certificado_matrimonio',
                'path_certificado_curso',
                'orientacion_certificado_bautismo',
                'orientacion_certificado_confirmacion',
                'orientacion_certificado_primera_comunion',
                'orientacion_certificado_matrimonio',
                'orientacion_certificado_curso',
            ]);
        });
    }
};
