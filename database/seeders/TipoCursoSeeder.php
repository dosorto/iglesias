<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCurso;

class TipoCursoSeeder extends Seeder
{
    public function run(): void
    {
        $tiposCurso = [
            ['nombre_curso' => 'Curso Bautismo',                 'descripcion_curso' => 'Primeros pasos hacia el Bautismo.'],
            ['nombre_curso' => 'Curso Primera Comunión',         'descripcion_curso' => 'Preparación para recibir el sacramento de la Primera Comunión.'],
            ['nombre_curso' => 'Curso Confirmación',             'descripcion_curso' => 'Formación espiritual para el sacramento de la Confirmación.'],
            ['nombre_curso' => 'Curso Prematrimonial',           'descripcion_curso' => 'Preparación para el sacramento del Matrimonio.'],
            ['nombre_curso' => 'Curso para Padrinos',            'descripcion_curso' => 'Formación y responsabilidad de padrinos en los sacramentos.'],
            ['nombre_curso' => 'Catequesis Infantil',            'descripcion_curso' => 'Enseñanza básica de la fe católica para niños.'],
            ['nombre_curso' => 'Catequesis Juvenil',             'descripcion_curso' => 'Formación cristiana dirigida a adolescentes y jóvenes.'],
            ['nombre_curso' => 'Escuela Bíblica',                'descripcion_curso' => 'Estudio y reflexión de las Sagradas Escrituras.'],
            ['nombre_curso' => 'Formación de Líderes Pastorales','descripcion_curso' => 'Capacitación para servidores y líderes de la comunidad parroquial.'],
            ['nombre_curso' => 'Curso de Liturgia',              'descripcion_curso' => 'Formación sobre el significado y práctica de la liturgia.'],
        ];

        foreach ($tiposCurso as $tipo) {
            TipoCurso::firstOrCreate(
                ['nombre_curso' => $tipo['nombre_curso']],
                ['descripcion_curso' => $tipo['descripcion_curso']]
            );
        }
    }
}
