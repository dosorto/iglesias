<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('constancias', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
    $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();

    $table->string('tipo_documento', 30); // BAUTISMO, CONFIRMACION, COMUNION, MATRIMONIO, CURSO, MIXTO
    $table->string('folio', 50)->nullable();
    $table->timestamp('fecha_emision')->useCurrent();

    $table->string('estado', 20)->default('VIGENTE');
    $table->text('motivo_anulacion')->nullable();

    $table->string('hash_code', 255)->nullable();
    $table->string('path_pdf', 250)->nullable();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('constancias');
    }
};