<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones_curso', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('curso_id')->constrained('cursos')->restrictOnDelete();
    $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();

    $table->date('fecha_inscripcion');
    $table->boolean('aprobado')->nullable();
    $table->boolean('certificado_emitido')->default(false);
    $table->date('fecha_certificado')->nullable();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();

    $table->unique(['curso_id', 'feligres_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_curso');
    }
};