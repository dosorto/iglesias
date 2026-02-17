<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoCurso;

class TipoCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //generar un tipo de curso de ejemplo
        TipoCurso::create([
            "nombre_curso" => "Curso de Programación",
            "descripcion_curso" => "Aprende los fundamentos de la programación con este curso introductorio.",
            "estado_curso" => "activo"
        ]);
    }
}
