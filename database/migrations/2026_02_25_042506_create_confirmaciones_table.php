<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('confirmaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
            $table->date('fecha_confirmacion');
            $table->string('lugar_confirmacion', 200)->nullable();

            $table->foreignId('encargado_id')->nullable()->constrained('encargado')->nullOnDelete();


            // Confirmado
            $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();

            // Familia
            $table->foreignId('padre_id')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('madre_id')->nullable()->constrained('feligres')->nullOnDelete();

            // Padrinos
            $table->foreignId('padrino_id')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('madrina_id')->nullable()->constrained('feligres')->nullOnDelete();

            // Ministro confirmante
            $table->foreignId('ministro_id')->nullable()->constrained('feligres')->nullOnDelete();

            // Control libro parroquial
            $table->string('libro_confirmacion', 50)->nullable();
            $table->string('folio', 50)->nullable();
            $table->string('partida_numero', 50)->nullable();
            $table->text('observaciones')->nullable();

            // Campos para el certificado
            $table->string('nota_marginal', 500)->nullable();
            $table->string('lugar_nacimiento', 150)->nullable();
            $table->string('lugar_expedicion', 150)->nullable();
            $table->date('fecha_expedicion')->nullable();

            // Auditoría
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