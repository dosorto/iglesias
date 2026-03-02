<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Todos los permisos ──────────────────────────────────────────
        $permissions = [
            // Roles
            ['name' => 'roles.view',    'display_name' => 'Ver Roles'],
            ['name' => 'roles.create',  'display_name' => 'Crear Roles'],
            ['name' => 'roles.edit',    'display_name' => 'Editar Roles'],
            ['name' => 'roles.delete',  'display_name' => 'Eliminar Roles'],

            // Usuarios
            ['name' => 'users.view',    'display_name' => 'Ver Usuarios'],
            ['name' => 'users.create',  'display_name' => 'Crear Usuarios'],
            ['name' => 'users.edit',    'display_name' => 'Editar Usuarios'],
            ['name' => 'users.delete',  'display_name' => 'Eliminar Usuarios'],

            // Audit
            ['name' => 'audit.view',    'display_name' => 'Ver Logs del Sistema'],
            ['name' => 'audit.export',  'display_name' => 'Exportar Logs del Sistema'],

            // Personas (solo ROOT)
            ['name' => 'personas.view',   'display_name' => 'Ver Personas'],
            ['name' => 'personas.create', 'display_name' => 'Crear Personas'],
            ['name' => 'personas.edit',   'display_name' => 'Editar Personas'],
            ['name' => 'personas.delete', 'display_name' => 'Eliminar Personas'],
            ['name' => 'personas.export', 'display_name' => 'Exportar Personas'],

            // Iglesias (solo ROOT)
            ['name' => 'iglesias.view',   'display_name' => 'Ver Iglesias'],
            ['name' => 'iglesias.create', 'display_name' => 'Crear Iglesias'],
            ['name' => 'iglesias.edit',   'display_name' => 'Editar Iglesias'],
            ['name' => 'iglesias.delete', 'display_name' => 'Eliminar Iglesias'],
            ['name' => 'iglesias.export', 'display_name' => 'Exportar Iglesias'],

            // Religion (solo ROOT)
            ['name' => 'religion.view',   'display_name' => 'Ver Religion'],
            ['name' => 'religion.create', 'display_name' => 'Crear Religion'],
            ['name' => 'religion.edit',   'display_name' => 'Editar Religion'],
            ['name' => 'religion.delete', 'display_name' => 'Eliminar Religion'],
            ['name' => 'religion.export', 'display_name' => 'Exportar Religion'],

            // Feligreses (ADMIN)
            ['name' => 'feligres.view',   'display_name' => 'Ver Feligreses'],
            ['name' => 'feligres.create', 'display_name' => 'Crear Feligreses'],
            ['name' => 'feligres.edit',   'display_name' => 'Editar Feligreses'],
            ['name' => 'feligres.delete', 'display_name' => 'Eliminar Feligreses'],

            // Encargados (ADMIN)
            ['name' => 'encargado.view',   'display_name' => 'Ver Encargados'],
            ['name' => 'encargado.create', 'display_name' => 'Crear Encargados'],
            ['name' => 'encargado.edit',   'display_name' => 'Editar Encargados'],
            ['name' => 'encargado.delete', 'display_name' => 'Eliminar Encargados'],

            // Instructores (ADMIN)
            ['name' => 'instructor.view',   'display_name' => 'Ver Instructores'],
            ['name' => 'instructor.create', 'display_name' => 'Crear Instructores'],
            ['name' => 'instructor.edit',   'display_name' => 'Editar Instructores'],
            ['name' => 'instructor.delete', 'display_name' => 'Eliminar Instructores'],

            // Tipo Cursos (ADMIN)
            ['name' => 'tipocurso.view',   'display_name' => 'Ver Tipos de Cursos'],
            ['name' => 'tipocurso.create', 'display_name' => 'Crear Tipos de Cursos'],
            ['name' => 'tipocurso.edit',   'display_name' => 'Editar Tipos de Cursos'],
            ['name' => 'tipocurso.delete', 'display_name' => 'Eliminar Tipos de Cursos'],
            ['name' => 'tipocurso.export', 'display_name' => 'Exportar Tipos de Cursos'],

            // Estudiantes (ADMIN)
            ['name' => 'estudiantes.view',   'display_name' => 'Ver Estudiantes'],
            ['name' => 'estudiantes.create', 'display_name' => 'Crear Estudiantes'],
            ['name' => 'estudiantes.edit',   'display_name' => 'Editar Estudiantes'],
            ['name' => 'estudiantes.delete', 'display_name' => 'Eliminar Estudiantes'],
            ['name' => 'estudiantes.export', 'display_name' => 'Exportar Estudiantes'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], ['display_name' => $p['display_name']]);
        }

        // ── Permisos por rol ────────────────────────────────────────────

        // ROOT: acceso total (personas, iglesias, religion + todo lo demás)
        $rootPermissions = Permission::all();

        // ADMIN: solo lo de su iglesia (NO personas, iglesias, religion)
        $adminPermissions = Permission::whereNotIn('name', [
            'personas.view',   'personas.create',  'personas.edit',   'personas.delete',  'personas.export',
            'iglesias.view',   'iglesias.create',  'iglesias.edit',   'iglesias.delete',  'iglesias.export',
            'religion.view',   'religion.create',  'religion.edit',   'religion.delete',  'religion.export',
        ])->get();

        // ── Crear roles ─────────────────────────────────────────────────
        $rootRole  = Role::firstOrCreate(['name' => 'root']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $rootRole->syncPermissions($rootPermissions);
        $adminRole->syncPermissions($adminPermissions);

        // ── Asignar root al usuario test@example.com ────────────────────
        $rootUser = User::where('email', 'test@example.com')->first();
        if ($rootUser) {
            $rootUser->syncRoles($rootRole);
        }
    }
}
