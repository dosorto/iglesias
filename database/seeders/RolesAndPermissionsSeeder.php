<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔹 Crear permisos
        $permissions = [
            ['name' => 'roles.view', 'display_name' => 'Ver Roles'],
            ['name' => 'roles.create', 'display_name' => 'Crear Roles'],
            ['name' => 'roles.edit', 'display_name' => 'Editar Roles'],
            ['name' => 'roles.delete', 'display_name' => 'Eliminar Roles'],

            ['name' => 'users.view', 'display_name' => 'Ver Usuarios'],
            ['name' => 'users.create', 'display_name' => 'Crear Usuarios'],
            ['name' => 'users.edit', 'display_name' => 'Editar Usuarios'],
            ['name' => 'users.delete', 'display_name' => 'Eliminar Usuarios'],

            ['name' => 'estudiantes.view', 'display_name' => 'Ver Estudiantes'],
            ['name' => 'estudiantes.create', 'display_name' => 'Crear Estudiantes'],
            ['name' => 'estudiantes.edit', 'display_name' => 'Editar Estudiantes'],
            ['name' => 'estudiantes.delete', 'display_name' => 'Eliminar Estudiantes'],
            ['name' => 'estudiantes.export', 'display_name' => 'Exportar Estudiantes (Excel)'],

            ['name' => 'personas.view', 'display_name' => 'Ver Personas'],
            ['name' => 'personas.create', 'display_name' => 'Crear Personas'],
            ['name' => 'personas.edit', 'display_name' => 'Editar Personas'],
            ['name' => 'personas.delete', 'display_name' => 'Eliminar Personas'],
            ['name' => 'personas.export', 'display_name' => 'Exportar Personas (Excel)'],
            ['name' => 'audit.view', 'display_name' => 'Ver Logs del Sistema'],
            ['name' => 'audit.export', 'display_name' => 'Exportar Logs del Sistema'],

            // iglesias
            ['name' => 'iglesias.view', 'display_name' => 'Ver iglesias'],
            ['name' => 'iglesias.create', 'display_name' => 'Crear iglesias'],
            ['name' => 'iglesias.edit', 'display_name' => 'Editar iglesias'],
            ['name' => 'iglesias.delete', 'display_name' => 'Eliminar iglesias'],
            ['name' => 'iglesias.export', 'display_name' => 'Exportar iglesias (Excel)'],
            ['name' => 'audit.view', 'display_name' => 'Ver Logs del Sistema'],
            ['name' => 'audit.export', 'display_name' => 'Exportar Logs del Sistema'],



            ['name' => 'religion.view', 'display_name' => 'Ver Religion'],
            ['name' => 'religion.create', 'display_name' => 'Crear Religion'],
            ['name' => 'religion.edit', 'display_name' => 'Editar Religion'],
            ['name' => 'religion.delete', 'display_name' => 'Eliminar Religion'],
            ['name' => 'religion.export', 'display_name' => 'Exportar Religion (Excel)'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                ['display_name' => $permissionData['display_name']]
            );
        }
        // 🔹 Crear rol admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 🔹 Asignar TODOS los permisos al admin
        $adminRole->syncPermissions(Permission::all());

        // 🔹 Asignar rol admin al usuario ID = 1
        $user = User::find(1);

        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
