<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('primeras_comuniones', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
    $table->date('fecha_primera_comunion');
    $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();

    $table->string('feligres_nombre', 250)->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->string('nombre_papa', 250)->nullable();
    $table->string('nombre_mama', 250)->nullable();

    // opcionales (personas)
    $table->string('catequista_nombre', 200)->nullable();
    $table->foreignId('catequista_id')->nullable()->constrained('personas')->nullOnDelete();

    $table->string('ministro_nombre', 200)->nullable();
    $table->foreignId('ministro_id')->nullable()->constrained('personas')->nullOnDelete();

    $table->string('parroco_nombre', 200)->nullable();
    $table->foreignId('parroco_id')->nullable()->constrained('personas')->nullOnDelete();

    $table->string('libro_comunion', 50)->nullable();
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
        Schema::dropIfExists('primeras_comuniones');
    }
};