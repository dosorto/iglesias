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
            "nombre_curso" => "Curso Bautismo",
            "descripcion_curso" => "Primeros pasos hacia el Bautismo.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
        "nombre_curso" => "Curso Bautismo",
        "descripcion_curso" => "Primeros pasos hacia el Bautismo.",
        "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Curso Primera Comunión",
            "descripcion_curso" => "Preparación para recibir el sacramento de la Primera Comunión.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Curso Confirmación",
            "descripcion_curso" => "Formación espiritual para el sacramento de la Confirmación.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Curso Prematrimonial",
            "descripcion_curso" => "Preparación para el sacramento del Matrimonio.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Curso para Padrinos",
            "descripcion_curso" => "Formación y responsabilidad de padrinos en los sacramentos.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Catequesis Infantil",
            "descripcion_curso" => "Enseñanza básica de la fe católica para niños.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Catequesis Juvenil",
            "descripcion_curso" => "Formación cristiana dirigida a adolescentes y jóvenes.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Escuela Bíblica",
            "descripcion_curso" => "Estudio y reflexión de las Sagradas Escrituras.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Formación de Líderes Pastorales",
            "descripcion_curso" => "Capacitación para servidores y líderes de la comunidad parroquial.",
            "estado_curso" => "activo"
        ]);

        TipoCurso::create([
            "nombre_curso" => "Curso de Liturgia",
            "descripcion_curso" => "Formación sobre el significado y práctica de la liturgia.",
            "estado_curso" => "activo"
        ]);
    }
}
