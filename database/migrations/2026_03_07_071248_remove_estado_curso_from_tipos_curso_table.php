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
        Schema::table('tipos_curso', function (Blueprint $table) {
            $table->dropColumn('estado_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_curso', function (Blueprint $table) {
            $table->string('estado_curso', 50)->default('activo')->after('descripcion_curso');
        });
    }
};
