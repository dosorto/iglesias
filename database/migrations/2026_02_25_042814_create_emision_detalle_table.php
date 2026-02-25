<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emision_detalle', function (Blueprint $table) {
    $table->id();

    // obligatoria (detalle sin cabecera no sirve)
    $table->foreignId('emision_id')->constrained('constancias')->restrictOnDelete();

    // polimórfico manual (no FK)
    $table->string('referencia_tipo', 30);
    $table->unsignedBigInteger('referencia_id');

    $table->text('notas')->nullable();

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();

    $table->index(['referencia_tipo', 'referencia_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('emision_detalle');
    }
};