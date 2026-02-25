<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BautismoSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('bautismos')) return;

        $iglesiaCol   = Schema::hasColumn('bautismos', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $encargadoCol = Schema::hasColumn('bautismos', 'encargado_id') ? 'encargado_id' : 'id_encargado';

        // bautizado principal
        $bautizadoCol = Schema::hasColumn('bautismos', 'bautizado_id') ? 'bautizado_id' : 'id_bautizado';

        // familiares
        $padreCol   = Schema::hasColumn('bautismos', 'padre_id') ? 'padre_id' : 'id_padre';
        $madreCol   = Schema::hasColumn('bautismos', 'madre_id') ? 'madre_id' : 'id_madre';
        $padrinoCol = Schema::hasColumn('bautismos', 'padrino_id') ? 'padrino_id' : 'id_padrino';
        $madrinaCol = Schema::hasColumn('bautismos', 'madrina_id') ? 'madrina_id' : 'id_madrina';

        DB::table('bautismos')->insert([
            $iglesiaCol => 1,
            'fecha_bautismo' => '2026-02-10',
            $encargadoCol => 1,     // encargado #1
            $bautizadoCol => 4,     // feligrés #4 bautizado
            $padreCol => 1,         // feligrés #1
            $madreCol => 2,         // feligrés #2
            $padrinoCol => 3,       // feligrés #3
            $madrinaCol => 6,       // feligrés #6
            'libro_bautismo' => 'Libro 1',
            'folio' => 'F-10',
            'partida_numero' => 'P-0010',
            'observaciones' => 'Bautismo de prueba (seed).',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}