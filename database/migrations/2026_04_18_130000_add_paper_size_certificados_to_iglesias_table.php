<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            if (! Schema::hasColumn('iglesias', 'paper_size_certificado')) {
                $table->string('paper_size_certificado', 20)
                    ->default('letter')
                    ->after('orientacion_certificado');
            }

            if (! Schema::hasColumn('iglesias', 'paper_size_certificado_bautismo')) {
                $table->string('paper_size_certificado_bautismo', 20)
                    ->default('letter')
                    ->after('orientacion_certificado_bautismo');
            }

            if (! Schema::hasColumn('iglesias', 'paper_size_certificado_confirmacion')) {
                $table->string('paper_size_certificado_confirmacion', 20)
                    ->default('letter')
                    ->after('orientacion_certificado_confirmacion');
            }

            if (! Schema::hasColumn('iglesias', 'paper_size_certificado_primera_comunion')) {
                $table->string('paper_size_certificado_primera_comunion', 20)
                    ->default('letter')
                    ->after('orientacion_certificado_primera_comunion');
            }

            if (! Schema::hasColumn('iglesias', 'paper_size_certificado_matrimonio')) {
                $table->string('paper_size_certificado_matrimonio', 20)
                    ->default('letter')
                    ->after('orientacion_certificado_matrimonio');
            }

            if (! Schema::hasColumn('iglesias', 'paper_size_certificado_curso')) {
                $table->string('paper_size_certificado_curso', 20)
                    ->default('letter')
                    ->after('orientacion_certificado_curso');
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'paper_size_certificado',
            'paper_size_certificado_bautismo',
            'paper_size_certificado_confirmacion',
            'paper_size_certificado_primera_comunion',
            'paper_size_certificado_matrimonio',
            'paper_size_certificado_curso',
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
