<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bautismos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('iglesia_id')->constrained('iglesias')->restrictOnDelete();
            $table->date('fecha_bautismo');

            $table->foreignId('encargado_id')->nullable()->constrained('encargado')->nullOnDelete();

            $table->foreignId('bautizado_id')->constrained('feligres')->restrictOnDelete();

            $table->foreignId('padre_id')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('madre_id')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('padrino_id')->nullable()->constrained('feligres')->nullOnDelete();
            $table->foreignId('madrina_id')->nullable()->constrained('feligres')->nullOnDelete();

            $table->string('libro_bautismo', 50)->nullable();
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
        Schema::dropIfExists('bautismos');
    }
};