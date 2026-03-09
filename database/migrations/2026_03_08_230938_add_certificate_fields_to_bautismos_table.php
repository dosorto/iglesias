<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            $table->string('nota_marginal', 500)->nullable()->after('observaciones');
            $table->string('lugar_expedicion', 150)->nullable()->after('nota_marginal');
            $table->date('fecha_expedicion')->nullable()->after('lugar_expedicion');
        });
    }

    public function down(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            $table->dropColumn(['nota_marginal', 'lugar_expedicion', 'fecha_expedicion']);
        });
    }
};
