<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('primeras_comuniones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_iglesia')->constrained('iglesias')->restrictOnDelete();
            $table->date('fecha_primera_comunion');

            $table->foreignId('encargado_id')->nullable()->constrained('encargado')->nullOnDelete();

            $table->foreignId('id_feligres')->constrained('feligres')->restrictOnDelete();

            $table->foreignId('id_catequista')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('id_ministro')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('id_parroco')->nullable()->constrained('feligres')->nullOnDelete();

            $table->string('libro_comunion', 50)->nullable();
            $table->string('folio', 50)->nullable();
            $table->string('partida_numero', 50)->nullable();
            $table->text('observaciones')->nullable();

            $table->string('nota_marginal', 500)->nullable();
            $table->string('lugar_celebracion', 150)->nullable();
            $table->string('lugar_expedicion', 150)->nullable();
            $table->date('fecha_expedicion')->nullable();

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