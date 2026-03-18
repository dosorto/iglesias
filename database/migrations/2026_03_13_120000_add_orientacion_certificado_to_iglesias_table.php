<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            $table->string('orientacion_certificado', 20)
                ->default('portrait')
                ->after('path_certificado_bautismo');
        });
    }

    public function down(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            $table->dropColumn('orientacion_certificado');
        });
    }
};
