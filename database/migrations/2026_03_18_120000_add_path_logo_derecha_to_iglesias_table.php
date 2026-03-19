<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            $table->string('path_logo_derecha')->nullable()->after('path_logo');
        });
    }

    public function down(): void
    {
        Schema::table('iglesias', function (Blueprint $table) {
            $table->dropColumn('path_logo_derecha');
        });
    }
};
