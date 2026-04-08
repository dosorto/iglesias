<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('curso_instructor')) {
            return;
        }

        Schema::create('curso_instructor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('instructores')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['curso_id', 'instructor_id']);
        });

        DB::table('cursos')
            ->whereNotNull('instructor_id')
            ->select(['id', 'instructor_id'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                $now = now();
                $payload = [];

                foreach ($rows as $row) {
                    $payload[] = [
                        'curso_id' => $row->id,
                        'instructor_id' => $row->instructor_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (! empty($payload)) {
                    DB::table('curso_instructor')->upsert(
                        $payload,
                        ['curso_id', 'instructor_id'],
                        ['updated_at']
                    );
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_instructor');
    }
};
