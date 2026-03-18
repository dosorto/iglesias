<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCurso;

class TenantTipoCursoSeeder extends Seeder
{
    /**
     * Tipos de curso base para toda parroquia nueva.
     */
    public function run(): void
    {
        $cursos = [
            // Esenciales
            [
                'nombre_curso'     => 'Catequesis para niños',
                'descripcion_curso' => 'Enseñanza de la fe católica para niños en edad escolar.',
            ],
            [
                'nombre_curso'     => 'Primera Comunión',
                'descripcion_curso' => 'Preparación para recibir el sacramento de la Primera Comunión.',
            ],
            [
                'nombre_curso'     => 'Confirmación',
                'descripcion_curso' => 'Formación espiritual para recibir el sacramento de la Confirmación.',
            ],
            [
                'nombre_curso'     => 'Preparación al Bautismo',
                'descripcion_curso' => 'Catequesis para padres y padrinos antes del Bautismo.',
            ],
            [
                'nombre_curso'     => 'Preparación matrimonial',
                'descripcion_curso' => 'Curso prematrimonial para parejas que desean casarse por la Iglesia.',
            ],

            // Muy importantes
            [
                'nombre_curso'     => 'Catequesis para adultos',
                'descripcion_curso' => 'Formación y profundización de la fe para adultos.',
            ],
            [
                'nombre_curso'     => 'RICA',
                'descripcion_curso' => 'Rito de Iniciación Cristiana de Adultos, para quienes desean incorporarse al catolicismo.',
            ],
            [
                'nombre_curso'     => 'Estudio bíblico',
                'descripcion_curso' => 'Lectura, estudio y reflexión de las Sagradas Escrituras.',
            ],
            [
                'nombre_curso'     => 'Grupo juvenil',
                'descripcion_curso' => 'Formación cristiana y espacio de comunidad para jóvenes y adolescentes.',
            ],

            // Complementarios
            [
                'nombre_curso'     => 'Retiros espirituales',
                'descripcion_curso' => 'Jornadas de oración y reflexión para el crecimiento espiritual.',
            ],
            [
                'nombre_curso'     => 'Oración y meditación',
                'descripcion_curso' => 'Grupos de oración contemplativa y meditación.',
            ],
            [
                'nombre_curso'     => 'Ministerio de música / coro',
                'descripcion_curso' => 'Formación para servidores del ministerio musical y coro parroquial.',
            ],
            [
                'nombre_curso'     => 'Voluntariado y servicio comunitario',
                'descripcion_curso' => 'Organización y formación de voluntarios para el servicio a la comunidad.',
            ],
        ];

        foreach ($cursos as $curso) {
            TipoCurso::firstOrCreate(
                ['nombre_curso' => $curso['nombre_curso']],
                ['descripcion_curso' => $curso['descripcion_curso']]
            );
        }
    }
}
