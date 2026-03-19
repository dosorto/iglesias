<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('primeras_comuniones')) {
            return;
        }

        if (Schema::hasColumn('primeras_comuniones', 'encargado_id')) {
            return;
        }

        Schema::table('primeras_comuniones', function (Blueprint $table) {
            $table->foreignId('encargado_id')
                ->nullable()
                ->after('fecha_primera_comunion')
                ->constrained('encargado')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('primeras_comuniones')) {
            return;
        }

        if (! Schema::hasColumn('primeras_comuniones', 'encargado_id')) {
            return;
        }

        Schema::table('primeras_comuniones', function (Blueprint $table) {
            $table->dropForeign(['encargado_id']);
            $table->dropColumn('encargado_id');
        });
    }
};
