<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('matrimonios', function (Blueprint $table) {
            // Add encargado FK
            if (! Schema::hasColumn('matrimonios', 'encargado_id')) {
                $table->foreignId('encargado_id')->nullable()->after('fecha_matrimonio')->constrained('encargado')->nullOnDelete();
            }

            // Add testigo FKs replacing old varchar columns
            if (Schema::hasColumn('matrimonios', 'testigo1')) {
                $table->dropColumn('testigo1');
            }
            if (Schema::hasColumn('matrimonios', 'testigo2')) {
                $table->dropColumn('testigo2');
            }
            if (Schema::hasColumn('matrimonios', 'nombre_padre')) {
                $table->dropColumn('nombre_padre');
            }

            if (! Schema::hasColumn('matrimonios', 'testigo1_id')) {
                $table->foreignId('testigo1_id')->nullable()->after('esposa_id')->constrained('feligres')->nullOnDelete();
            }
            if (! Schema::hasColumn('matrimonios', 'testigo2_id')) {
                $table->foreignId('testigo2_id')->nullable()->after('testigo1_id')->constrained('feligres')->nullOnDelete();
            }

            // Add certificate fields
            if (! Schema::hasColumn('matrimonios', 'nota_marginal')) {
                $table->string('nota_marginal', 500)->nullable()->after('observaciones');
            }
            if (! Schema::hasColumn('matrimonios', 'lugar_expedicion')) {
                $table->string('lugar_expedicion', 150)->nullable()->after('nota_marginal');
            }
            if (! Schema::hasColumn('matrimonios', 'fecha_expedicion')) {
                $table->date('fecha_expedicion')->nullable()->after('lugar_expedicion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matrimonios', function (Blueprint $table) {
            $table->dropForeign(['encargado_id']);
            $table->dropForeign(['testigo1_id']);
            $table->dropForeign(['testigo2_id']);
            $table->dropColumn(['encargado_id', 'testigo1_id', 'testigo2_id', 'nota_marginal', 'lugar_expedicion', 'fecha_expedicion']);
            $table->string('nombre_padre', 250)->nullable();
            $table->string('testigo1', 250)->nullable();
            $table->string('testigo2', 250)->nullable();
        });
    }
};
