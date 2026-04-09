<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            $table->string('parroco_celebrante', 150)
                ->nullable()
                ->after('nota_marginal');
        });
    }

    public function down(): void
    {
        Schema::table('bautismos', function (Blueprint $table) {
            $table->dropColumn('parroco_celebrante');
        });
    }
};
