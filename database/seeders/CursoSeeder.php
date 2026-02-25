<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('cursos')) return;

        $encargadoTable = Schema::hasTable('encargados') ? 'encargados' : 'encargado';
        $encargadoCol   = Schema::hasColumn('cursos', 'encargado_id') ? 'encargado_id' : 'id_encargado';

        $iglesiaCol     = Schema::hasColumn('cursos', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';

        // tipo_curso puede ser tipo_curso_id o id_tipo_curso
        $tipoCursoCol   = Schema::hasColumn('cursos', 'tipo_curso_id') ? 'tipo_curso_id' : (Schema::hasColumn('cursos','id_tipo_curso') ? 'id_tipo_curso' : 'id_tipo_curso');

        $instructorCol  = Schema::hasColumn('cursos', 'instructor_id') ? 'instructor_id' : 'id_instructor';

        DB::table('cursos')->insert([
            $encargadoCol => 1, // encargado #1
            $iglesiaCol   => 1, // iglesia #1
            $tipoCursoCol => 1, // tipo curso #1 (ajusta si tu TipoCursoSeeder crea otros)
            'nombre'      => 'Curso Bautismo - Grupo A',
            'fecha_inicio'=> '2026-03-01',
            'fecha_fin'   => '2026-03-20',
            $instructorCol=> 1, // instructor #1
            'estado'      => 'Activo',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}