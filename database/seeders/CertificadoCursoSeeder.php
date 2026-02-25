<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CertificadoCursoSeeder extends Seeder
{
    public function run(): void
    {
        $table = Schema::hasTable('certificados_curso') ? 'certificados_curso'
              : (Schema::hasTable('certificado_curso') ? 'certificado_curso' : null);

        if (!$table) return;

        $cursoCol = Schema::hasColumn($table, 'curso_id') ? 'curso_id' : 'id_curso';

        DB::table($table)->insert([
            $cursoCol => 1,
            'nombre_curso' => 'Curso Bautismo - Grupo A',
            'nombre_iglesia' => 'Iglesia Demo',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-03-20',
            'hash_code' => 'HASH-CURSO-0001',
            'instructor_nombre' => 'Instructor Demo',
            'path_firma_principal' => 'firmas/encargado_principal.png',
            'path_firma_instructor' => 'firmas/instructor_1.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}