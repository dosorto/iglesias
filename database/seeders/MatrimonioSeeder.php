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

        // Columnas con nombres alternativos (migraciones antiguas vs nuevas)
        $iglesiaCol = Schema::hasColumn('matrimonios', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $esposoCol  = Schema::hasColumn('matrimonios', 'esposo_id')  ? 'esposo_id'  : 'id_esposo';
        $esposaCol  = Schema::hasColumn('matrimonios', 'esposa_id')  ? 'esposa_id'  : 'id_esposa';

        $data = [
            $iglesiaCol        => 1,
            'fecha_matrimonio' => '2026-02-25',
            $esposoCol         => 1,
            $esposaCol         => 2,
            'libro_matrimonio' => 'Libro M1',
            'folio'            => 'FM-01',
            'partida_numero'   => 'PM-0001',
            'observaciones'    => 'Matrimonio de prueba (seed).',
            'created_at'       => now(),
            'updated_at'       => now(),
        ];

        // Esquema ANTIGUO: columnas varchar de texto libre
        if (Schema::hasColumn('matrimonios', 'nombre_padre')) {
            $data['nombre_padre'] = 'Padre del esposo (seed)';
        }
        if (Schema::hasColumn('matrimonios', 'testigo1')) {
            $data['testigo1'] = 'Testigo 1 (seed)';
        }
        if (Schema::hasColumn('matrimonios', 'testigo2')) {
            $data['testigo2'] = 'Testigo 2 (seed)';
        }

        // Esquema NUEVO: FKs a feligres
        if (Schema::hasColumn('matrimonios', 'testigo1_id')) {
            $data['testigo1_id'] = null; // sin feligrés de prueba asignado
        }
        if (Schema::hasColumn('matrimonios', 'testigo2_id')) {
            $data['testigo2_id'] = null;
        }
        if (Schema::hasColumn('matrimonios', 'encargado_id')) {
            $data['encargado_id'] = null;
        }

        DB::table('matrimonios')->insert($data);
    }
}