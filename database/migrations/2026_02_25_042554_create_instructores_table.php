<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instructores', function (Blueprint $table) {
    $table->id();

    // obligatoria
    $table->foreignId('feligres_id')->constrained('feligres')->restrictOnDelete();
    $table->string('path_firma', 200);

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();

    $table->unique('feligres_id');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('instructores');
    }
};