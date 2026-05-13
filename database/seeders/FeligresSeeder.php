<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeligresSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $feligreses = array_map(fn($idPersona) => [
            'id_persona'    => $idPersona,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2024-01-01',
            'estado'        => 'Activo',
            'created_at'    => $now,
            'updated_at'    => $now,
        ], range(1, 26));

        DB::table('feligres')->insert($feligreses);
    }
}
