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
        Schema::create('iglesias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->text('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 200)->nullable()->index();
            $table->string('parroco_nombre', 200);
            $table->string('estado', 20);
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
        Schema::dropIfExists('iglesias');
    }
};
