<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documentos_generados', function (Blueprint $table) {
            $table->string('path_pdf', 255)->nullable()->change();
            $table->string('codigo_verificacion', 32)->nullable()->after('payload');
            $table->string('hash_payload', 64)->nullable()->after('codigo_verificacion');

            $table->index('codigo_verificacion', 'doc_generados_codigo_idx');
        });
    }

    public function down(): void
    {
        Schema::table('documentos_generados', function (Blueprint $table) {
            $table->dropIndex('doc_generados_codigo_idx');
            $table->dropColumn(['codigo_verificacion', 'hash_payload']);
        });
    }
};
