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
        Schema::create('tipo_curso', function (Blueprint $table) {
            $table->id();
            $table->string("nombre_curso", 150);
            $table->text("descripcion_curso")->nullable();
            $table->string("estado_curso", 50)->default("activo");
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
        Schema::dropIfExists('tipo_curso');
    }
};
