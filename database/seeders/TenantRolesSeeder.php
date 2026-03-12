<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TenantRolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Solo permisos que admin puede tener en el tenant
        $adminPermisos = [
            ['name' => 'roles.view',    'display_name' => 'Ver Roles'],
            ['name' => 'roles.create',  'display_name' => 'Crear Roles'],
            ['name' => 'roles.edit',    'display_name' => 'Editar Roles'],
            ['name' => 'roles.delete',  'display_name' => 'Eliminar Roles'],

            ['name' => 'users.view',    'display_name' => 'Ver Usuarios'],
            ['name' => 'users.create',  'display_name' => 'Crear Usuarios'],
            ['name' => 'users.edit',    'display_name' => 'Editar Usuarios'],
            ['name' => 'users.delete',  'display_name' => 'Eliminar Usuarios'],

            ['name' => 'audit.view',    'display_name' => 'Ver Logs del Sistema'],
            ['name' => 'audit.export',  'display_name' => 'Exportar Logs del Sistema'],

            ['name' => 'feligres.view',   'display_name' => 'Ver Feligreses'],
            ['name' => 'feligres.create', 'display_name' => 'Crear Feligreses'],
            ['name' => 'feligres.edit',   'display_name' => 'Editar Feligreses'],
            ['name' => 'feligres.delete', 'display_name' => 'Eliminar Feligreses'],

            ['name' => 'encargado.view',   'display_name' => 'Ver Encargados'],
            ['name' => 'encargado.create', 'display_name' => 'Crear Encargados'],
            ['name' => 'encargado.edit',   'display_name' => 'Editar Encargados'],
            ['name' => 'encargado.delete', 'display_name' => 'Eliminar Encargados'],

            ['name' => 'instructor.view',   'display_name' => 'Ver Instructores'],
            ['name' => 'instructor.create', 'display_name' => 'Crear Instructores'],
            ['name' => 'instructor.edit',   'display_name' => 'Editar Instructores'],
            ['name' => 'instructor.delete', 'display_name' => 'Eliminar Instructores'],

            ['name' => 'tipocurso.view',   'display_name' => 'Ver Tipos de Cursos'],
            ['name' => 'tipocurso.create', 'display_name' => 'Crear Tipos de Cursos'],
            ['name' => 'tipocurso.edit',   'display_name' => 'Editar Tipos de Cursos'],
            ['name' => 'tipocurso.delete', 'display_name' => 'Eliminar Tipos de Cursos'],
            ['name' => 'tipocurso.export', 'display_name' => 'Exportar Tipos de Cursos'],

            ['name' => 'estudiantes.view',   'display_name' => 'Ver Estudiantes'],
            ['name' => 'estudiantes.create', 'display_name' => 'Crear Estudiantes'],
            ['name' => 'estudiantes.edit',   'display_name' => 'Editar Estudiantes'],
            ['name' => 'estudiantes.delete', 'display_name' => 'Eliminar Estudiantes'],
            ['name' => 'estudiantes.export', 'display_name' => 'Exportar Estudiantes'],

            ['name' => 'bautismo.view',   'display_name' => 'Ver Bautismos'],
            ['name' => 'bautismo.create', 'display_name' => 'Crear Bautismos'],
            ['name' => 'bautismo.edit',   'display_name' => 'Editar Bautismos'],
            ['name' => 'bautismo.delete', 'display_name' => 'Eliminar Bautismos'],
            ['name' => 'bautismo.export', 'display_name' => 'Exportar Bautismos'],

            ['name' => 'primera-comunion.view',   'display_name' => 'Ver Primera Comunion'],
            ['name' => 'primera-comunion.create', 'display_name' => 'Crear Primera Comunion'],
            ['name' => 'primera-comunion.edit',   'display_name' => 'Editar Primera Comunion'],
            ['name' => 'primera-comunion.delete', 'display_name' => 'Eliminar Primera Comunion'],
            ['name' => 'primera-comunion.export', 'display_name' => 'Exportar Primera Comunion'],

            ['name' => 'curso.view',   'display_name' => 'Ver Cursos'],
            ['name' => 'curso.create', 'display_name' => 'Crear Cursos'],
            ['name' => 'curso.edit',   'display_name' => 'Editar Cursos'],
            ['name' => 'curso.delete', 'display_name' => 'Eliminar Cursos'],
            ['name' => 'curso.export', 'display_name' => 'Exportar Cursos'],

            ['name' => 'inscripcion-curso.view',   'display_name' => 'Ver Inscripciones de Curso'],
            ['name' => 'inscripcion-curso.create', 'display_name' => 'Crear Inscripciones de Curso'],
            ['name' => 'inscripcion-curso.edit',   'display_name' => 'Editar Inscripciones de Curso'],
            ['name' => 'inscripcion-curso.delete', 'display_name' => 'Eliminar Inscripciones de Curso'],
            ['name' => 'inscripcion-curso.export', 'display_name' => 'Exportar Inscripciones de Curso'],
            
        ];

        foreach ($adminPermisos as $p) {
            Permission::updateOrCreate(['name' => $p['name']], ['display_name' => $p['display_name']]);
        }

        // Admin solo tiene estos permisos — SIN personas, iglesias, religion
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
    }
}