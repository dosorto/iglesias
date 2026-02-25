<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InscripcionCursoSeeder extends Seeder
{
    public function run(): void
    {
        $table = Schema::hasTable('inscripciones_curso') ? 'inscripciones_curso' : (Schema::hasTable('inscripciones_cursos') ? 'inscripciones_cursos' : null);
        if (!$table) return;

        $cursoCol    = Schema::hasColumn($table, 'curso_id') ? 'curso_id' : 'id_curso';
        $feligresCol = Schema::hasColumn($table, 'feligres_id') ? 'feligres_id' : 'id_feligres';

        DB::table($table)->insert([
            $cursoCol => 1,
            $feligresCol => 3, // feligrés #3
            'fecha_inscripcion' => '2026-03-01',
            'aprobado' => null,
            'certificado_emitido' => false,
            'fecha_certificado' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}