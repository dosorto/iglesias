<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('encargado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_feligres')->constrained('feligres');
            $table->string('path_firma_principal')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->integer("created_by")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encargado');
    }
};
