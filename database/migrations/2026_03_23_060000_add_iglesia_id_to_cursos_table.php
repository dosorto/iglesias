<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('cursos')) {
            return;
        }

        if (! Schema::hasColumn('cursos', 'iglesia_id')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->foreignId('iglesia_id')
                    ->nullable()
                    ->after('estado')
                    ->constrained('iglesias')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('cursos') || ! Schema::hasColumn('cursos', 'iglesia_id')) {
            return;
        }

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('iglesia_id');
        });
    }
};
