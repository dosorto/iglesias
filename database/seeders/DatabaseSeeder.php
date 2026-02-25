<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1) Usuario base
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call(RolesAndPermissionsSeeder::class);

        // 3) Catálogos/base (antes de tablas que dependan de ellos)
        $this->call(TipoCursoSeeder::class);
        $this->call(IglesiaSeeder::class);
        $this->call(ReligionSeeder::class);
    }
}
