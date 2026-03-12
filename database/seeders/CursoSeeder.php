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

        $enc = Schema::hasColumn('cursos', 'encargado_id')  ? 'encargado_id'  : 'id_encargado';
        $ig  = Schema::hasColumn('cursos', 'iglesia_id')    ? 'iglesia_id'    : 'id_iglesia';
        $tc  = Schema::hasColumn('cursos', 'tipo_curso_id') ? 'tipo_curso_id' : 'id_tipo_curso';
        $ins = Schema::hasColumn('cursos', 'instructor_id') ? 'instructor_id' : 'id_instructor';

        $cursos = [
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 1,  // Curso Bautismo
                $ins          => 1,
                'nombre'      => 'Curso Bautismo – Grupo A',
                'fecha_inicio'=> '2026-01-06',
                'fecha_fin'   => '2026-01-24',
                'estado'      => 'Finalizado',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 3,  // Curso Primera Comunión
                $ins          => 1,
                'nombre'      => 'Curso Primera Comunión – Grupo A',
                'fecha_inicio'=> '2026-01-10',
                'fecha_fin'   => '2026-02-28',
                'estado'      => 'Finalizado',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 4,  // Curso Confirmación
                $ins          => 1,
                'nombre'      => 'Curso Confirmación – Grupo A',
                'fecha_inicio'=> '2026-02-03',
                'fecha_fin'   => '2026-04-15',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 5,  // Curso Prematrimonial
                $ins          => 1,
                'nombre'      => 'Curso Prematrimonial – Grupo A',
                'fecha_inicio'=> '2026-03-01',
                'fecha_fin'   => '2026-03-29',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 7,  // Catequesis Infantil
                $ins          => 1,
                'nombre'      => 'Catequesis Infantil – Ciclo 2026',
                'fecha_inicio'=> '2026-02-09',
                'fecha_fin'   => '2026-11-30',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                $enc          => 1,
                $ig           => 1,
                $tc           => 1,  // Curso Bautismo
                $ins          => 1,
                'nombre'      => 'Curso Bautismo – Grupo B',
                'fecha_inicio'=> '2026-04-01',
                'fecha_fin'   => '2026-04-18',
                'estado'      => 'Pendiente',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('cursos')->insert($cursos);
    }
}