<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('confirmaciones', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
    $table->string('lugar_confirmacion', 200)->nullable();
    $table->date('fecha_confirmacion');
    $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();

    $table->string('nombre_feligres', 250)->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->string('nombre_padre', 250)->nullable();
    $table->string('nombre_madre', 250)->nullable();
    $table->string('padrino_madrina', 250)->nullable();

    // opcional
    $table->string('ministro_confirmacion_nombre', 200)->nullable();
    $table->foreignId('ministro_confirmacion_id')->nullable()->constrained('personas')->nullOnDelete();

    $table->string('libro_confirmacion', 50)->nullable();
    $table->string('folio', 50)->nullable();
    $table->string('partida_numero', 50)->nullable();
    $table->text('observaciones')->nullable();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('confirmaciones');
    }
};