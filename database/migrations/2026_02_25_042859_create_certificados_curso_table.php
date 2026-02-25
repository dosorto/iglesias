<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificados_curso', function (Blueprint $table) {
    $table->id();

    // obligatoria
    $table->foreignId('curso_id')->constrained('cursos')->restrictOnDelete();

    $table->string('nombre_curso', 200);
    $table->string('nombre_iglesia', 200)->nullable();
    $table->date('fecha_inicio')->nullable();
    $table->date('fecha_fin')->nullable();

    $table->string('hash_code', 255)->nullable();
    $table->string('instructor_nombre', 200)->nullable();

    $table->string('path_firma_principal', 200)->nullable();
    $table->string('path_firma_instructor', 200)->nullable();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados_curso');
    }
};