<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfirmacionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('confirmaciones')) return;

        $iglesiaCol  = Schema::hasColumn('confirmaciones', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $feligresCol = Schema::hasColumn('confirmaciones', 'feligres_id') ? 'feligres_id' : 'id_feligres';

        $ministroIdCol = Schema::hasColumn('confirmaciones', 'ministro_confirmacion_id')
            ? 'ministro_confirmacion_id'
            : 'id_ministro_confirmacion';

        DB::table('confirmaciones')->insert([
            $iglesiaCol => 1,
            'lugar_confirmacion' => 'Templo Principal',
            'fecha_confirmacion' => '2026-02-20',
            $feligresCol => 6,

            'nombre_feligres' => 'Confirmado (seed)',
            'fecha_nacimiento' => '1998-08-19',
            'nombre_padre' => 'Padre (seed)',
            'nombre_madre' => 'Madre (seed)',
            'padrino_madrina' => 'Padrino/Madrina (seed)',

            'ministro_confirmacion_nombre' => 'Obispo Demo',
            $ministroIdCol => 4, // persona #4

            'libro_confirmacion' => 'Libro K1',
            'folio' => 'FK-02',
            'partida_numero' => 'PK-0002',
            'observaciones' => 'Confirmación de prueba (seed).',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}