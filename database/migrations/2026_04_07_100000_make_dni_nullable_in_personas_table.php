<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('personas') || ! Schema::hasColumn('personas', 'dni')) {
            return;
        }

        Schema::table('personas', function (Blueprint $table) {
            $table->string('dni', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('personas') || ! Schema::hasColumn('personas', 'dni')) {
            return;
        }

        Schema::table('personas', function (Blueprint $table) {
            $table->string('dni', 20)->nullable(false)->change();
        });
    }
};
