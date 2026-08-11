<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bautismos')) {
            return;
        }

        if (Schema::hasColumn('bautismos', 'ministro_id')) {
            return;
        }

        Schema::table('bautismos', function (Blueprint $table) {
            $table->foreignId('ministro_id')
                ->nullable()
                ->after('madrina_id')
                ->constrained('feligres')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bautismos')) {
            return;
        }

        Schema::table('bautismos', function (Blueprint $table) {
            if (Schema::hasColumn('bautismos', 'ministro_id')) {
                $table->dropConstrainedForeignId('ministro_id');
            }
        });
    }
};
