<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('encargado_id')->constrained('encargado')->restrictOnDelete();
    $table->foreignId('tipo_curso_id')->constrained('tipos_curso')->restrictOnDelete();
    $table->foreignId('instructor_id')->constrained('instructores')->restrictOnDelete();

    $table->string('nombre', 200);
    $table->date('fecha_inicio')->nullable();
    $table->date('fecha_fin')->nullable();
    $table->string('estado', 20)->default('Activo'); // Activo, Finalizado, Cancelado
    $table->foreignId('iglesia_id')->nullable()->constrained('iglesias')->nullOnDelete();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};