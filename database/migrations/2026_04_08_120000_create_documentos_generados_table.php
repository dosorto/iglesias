<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos_generados', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 80);
            $table->string('fuente_tipo', 120);
            $table->unsignedBigInteger('fuente_id');
            $table->unsignedBigInteger('iglesia_id')->nullable();
            $table->dateTime('fecha_emision');
            $table->string('nombre_archivo', 200);
            $table->string('path_pdf', 255);
            $table->json('payload')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tipo_documento', 'fuente_tipo', 'fuente_id'], 'doc_generados_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_generados');
    }
};
