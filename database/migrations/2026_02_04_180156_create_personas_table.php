<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20)->index();
            $table->string('primer_nombre', 150);
            $table->string('segundo_nombre', 150)->nullable();
            $table->string('primer_apellido', 100);
            $table->string('segundo_apellido', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 255)->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};