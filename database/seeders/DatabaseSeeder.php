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

        // 2) Roles y permisos (siempre primero para evitar errores de integridad referencial)
        $this->call(RolesAndPermissionsSeeder::class);

        // 3) Catálogos/base (antes de tablas que dependan de ellos para evitar errores de integridad referencial)
        $this->call(TipoCursoSeeder::class);
        $this->call(ReligionSeeder::class);

        // 4) Entidades base
        $this->call(IglesiaSeeder::class);
        $this->call(PersonaSeeder::class);

        // 5) Relaciones base
        $this->call(FeligresSeeder::class);

        // 6) Dependientes de feligreses
        $this->call(EncargadoSeeder::class);
        $this->call(InstructorSeeder::class);

        // 7) Cursos e inscripciones
        $this->call(CursoSeeder::class);
        $this->call(InscripcionCursoSeeder::class);

        // 8) Sacramentos
        $this->call(BautismoSeeder::class);
        $this->call(PrimeraComunionSeeder::class);
        $this->call(ConfirmacionSeeder::class);
        $this->call(MatrimonioSeeder::class);

        // 9) Emisiones / certificados
        $this->call(ConstanciaSeeder::class);
        $this->call(EmisionDetalleSeeder::class);
        $this->call(CertificadoCursoSeeder::class);

        $this->call(TenantRolesSeeder::class);
    }
}