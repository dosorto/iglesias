<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('confirmaciones')) {
            return;
        }

        $missingEncargadoId = ! Schema::hasColumn('confirmaciones', 'encargado_id');
        $missingPadreId = ! Schema::hasColumn('confirmaciones', 'padre_id');
        $missingMadreId = ! Schema::hasColumn('confirmaciones', 'madre_id');
        $missingPadrinoId = ! Schema::hasColumn('confirmaciones', 'padrino_id');
        $missingMadrinaId = ! Schema::hasColumn('confirmaciones', 'madrina_id');
        $missingMinistroId = ! Schema::hasColumn('confirmaciones', 'ministro_id');
        $missingNotaMarginal = ! Schema::hasColumn('confirmaciones', 'nota_marginal');
        $missingLugarNacimiento = ! Schema::hasColumn('confirmaciones', 'lugar_nacimiento');
        $missingLugarExpedicion = ! Schema::hasColumn('confirmaciones', 'lugar_expedicion');
        $missingFechaExpedicion = ! Schema::hasColumn('confirmaciones', 'fecha_expedicion');

        if (! (
            $missingEncargadoId ||
            $missingPadreId ||
            $missingMadreId ||
            $missingPadrinoId ||
            $missingMadrinaId ||
            $missingMinistroId ||
            $missingNotaMarginal ||
            $missingLugarNacimiento ||
            $missingLugarExpedicion ||
            $missingFechaExpedicion
        )) {
            return;
        }

        Schema::table('confirmaciones', function (Blueprint $table) use (
            $missingEncargadoId,
            $missingPadreId,
            $missingMadreId,
            $missingPadrinoId,
            $missingMadrinaId,
            $missingMinistroId,
            $missingNotaMarginal,
            $missingLugarNacimiento,
            $missingLugarExpedicion,
            $missingFechaExpedicion
        ) {
            if ($missingEncargadoId) {
                $table->foreignId('encargado_id')->nullable()->constrained('encargado')->nullOnDelete();
            }

            if ($missingPadreId) {
                $table->foreignId('padre_id')->nullable()->constrained('feligres')->nullOnDelete();
            }

            if ($missingMadreId) {
                $table->foreignId('madre_id')->nullable()->constrained('feligres')->nullOnDelete();
            }

            if ($missingPadrinoId) {
                $table->foreignId('padrino_id')->nullable()->constrained('feligres')->nullOnDelete();
            }

            if ($missingMadrinaId) {
                $table->foreignId('madrina_id')->nullable()->constrained('feligres')->nullOnDelete();
            }

            if ($missingMinistroId) {
                $table->foreignId('ministro_id')->nullable()->constrained('feligres')->nullOnDelete();
            }

            if ($missingNotaMarginal) {
                $table->string('nota_marginal', 500)->nullable();
            }

            if ($missingLugarNacimiento) {
                $table->string('lugar_nacimiento', 150)->nullable();
            }

            if ($missingLugarExpedicion) {
                $table->string('lugar_expedicion', 150)->nullable();
            }

            if ($missingFechaExpedicion) {
                $table->date('fecha_expedicion')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('confirmaciones')) {
            return;
        }

        Schema::table('confirmaciones', function (Blueprint $table) {
            if (Schema::hasColumn('confirmaciones', 'encargado_id')) {
                $table->dropConstrainedForeignId('encargado_id');
            }

            if (Schema::hasColumn('confirmaciones', 'padre_id')) {
                $table->dropConstrainedForeignId('padre_id');
            }

            if (Schema::hasColumn('confirmaciones', 'madre_id')) {
                $table->dropConstrainedForeignId('madre_id');
            }

            if (Schema::hasColumn('confirmaciones', 'padrino_id')) {
                $table->dropConstrainedForeignId('padrino_id');
            }

            if (Schema::hasColumn('confirmaciones', 'madrina_id')) {
                $table->dropConstrainedForeignId('madrina_id');
            }

            if (Schema::hasColumn('confirmaciones', 'ministro_id')) {
                $table->dropConstrainedForeignId('ministro_id');
            }

            if (Schema::hasColumn('confirmaciones', 'nota_marginal')) {
                $table->dropColumn('nota_marginal');
            }

            if (Schema::hasColumn('confirmaciones', 'lugar_nacimiento')) {
                $table->dropColumn('lugar_nacimiento');
            }

            if (Schema::hasColumn('confirmaciones', 'lugar_expedicion')) {
                $table->dropColumn('lugar_expedicion');
            }

            if (Schema::hasColumn('confirmaciones', 'fecha_expedicion')) {
                $table->dropColumn('fecha_expedicion');
            }
        });
    }
};
