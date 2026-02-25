<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('matrimonios', function (Blueprint $table) {
    $table->id();

    // obligatorias
    $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
    $table->date('fecha_matrimonio');

    // en tu diagrama no es NOT NULL, lo dejo opcional
    $table->foreignId('esposo_id')->nullable()->constrained('feligres')->nullOnDelete();
    $table->foreignId('esposa_id')->nullable()->constrained('feligres')->nullOnDelete();

    $table->string('nombre_padre', 250)->nullable();
    $table->string('testigo1', 250)->nullable();
    $table->string('testigo2', 250)->nullable();

    $table->string('libro_matrimonio', 50)->nullable();
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
        Schema::dropIfExists('matrimonios');
    }
};