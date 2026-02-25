<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MatrimonioSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('matrimonios')) return;

        $iglesiaCol = Schema::hasColumn('matrimonios', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $esposoCol  = Schema::hasColumn('matrimonios', 'esposo_id') ? 'esposo_id' : 'id_esposo';
        $esposaCol  = Schema::hasColumn('matrimonios', 'esposa_id') ? 'esposa_id' : 'id_esposa';

        DB::table('matrimonios')->insert([
            $iglesiaCol => 1,
            'fecha_matrimonio' => '2026-02-25',
            $esposoCol => 1,
            $esposaCol => 2,

            'nombre_padre' => 'Padre del esposo (seed)',
            'testigo1' => 'Testigo 1 (seed)',
            'testigo2' => 'Testigo 2 (seed)',

            'libro_matrimonio' => 'Libro M1',
            'folio' => 'FM-01',
            'partida_numero' => 'PM-0001',
            'observaciones' => 'Matrimonio de prueba (seed).',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}