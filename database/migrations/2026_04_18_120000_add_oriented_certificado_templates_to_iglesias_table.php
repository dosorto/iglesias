<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            if (! Schema::hasColumn('iglesias', 'path_certificado_bautismo_portrait')) {
                $table->string('path_certificado_bautismo_portrait')->nullable()->after('path_certificado_bautismo');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_bautismo_landscape')) {
                $table->string('path_certificado_bautismo_landscape')->nullable()->after('path_certificado_bautismo_portrait');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_confirmacion_portrait')) {
                $table->string('path_certificado_confirmacion_portrait')->nullable()->after('path_certificado_confirmacion');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_confirmacion_landscape')) {
                $table->string('path_certificado_confirmacion_landscape')->nullable()->after('path_certificado_confirmacion_portrait');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_primera_comunion_portrait')) {
                $table->string('path_certificado_primera_comunion_portrait')->nullable()->after('path_certificado_primera_comunion');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_primera_comunion_landscape')) {
                $table->string('path_certificado_primera_comunion_landscape')->nullable()->after('path_certificado_primera_comunion_portrait');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_matrimonio_portrait')) {
                $table->string('path_certificado_matrimonio_portrait')->nullable()->after('path_certificado_matrimonio');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_matrimonio_landscape')) {
                $table->string('path_certificado_matrimonio_landscape')->nullable()->after('path_certificado_matrimonio_portrait');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_curso_portrait')) {
                $table->string('path_certificado_curso_portrait')->nullable()->after('path_certificado_curso');
            }

            if (! Schema::hasColumn('iglesias', 'path_certificado_curso_landscape')) {
                $table->string('path_certificado_curso_landscape')->nullable()->after('path_certificado_curso_portrait');
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'path_certificado_bautismo_portrait',
            'path_certificado_bautismo_landscape',
            'path_certificado_confirmacion_portrait',
            'path_certificado_confirmacion_landscape',
            'path_certificado_primera_comunion_portrait',
            'path_certificado_primera_comunion_landscape',
            'path_certificado_matrimonio_portrait',
            'path_certificado_matrimonio_landscape',
            'path_certificado_curso_portrait',
            'path_certificado_curso_landscape',
        ];

        $existingColumns = array_values(array_filter(
            $columns,
            fn (string $column): bool => Schema::hasColumn('iglesias', $column)
        ));

        if ($existingColumns === []) {
            return;
        }

        Schema::table('iglesias', function (Blueprint $table) use ($existingColumns) {
            $table->dropColumn($existingColumns);
        });
    }
};
