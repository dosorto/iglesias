<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1) Roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        // 2) Catálogos base
        $this->call(AppSettingSeeder::class);
        $this->call(TipoCursoSeeder::class);
        $this->call(ReligionSeeder::class);

        // 3) Roles del tenant
        $this->call(TenantRolesSeeder::class);
    }
}