<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrimeraComunionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('primeras_comuniones')) return;

        $iglesiaCol    = Schema::hasColumn('primeras_comuniones', 'iglesia_id')    ? 'iglesia_id'    : 'id_iglesia';
        $feligresCol   = Schema::hasColumn('primeras_comuniones', 'feligres_id')   ? 'feligres_id'   : 'id_feligres';
        $catequistaCol = Schema::hasColumn('primeras_comuniones', 'catequista_id') ? 'catequista_id' : 'id_catequista';
        $ministroCol   = Schema::hasColumn('primeras_comuniones', 'ministro_id')   ? 'ministro_id'   : 'id_ministro';
        $parrocoCol    = Schema::hasColumn('primeras_comuniones', 'parroco_id')    ? 'parroco_id'    : 'id_parroco';

        DB::table('primeras_comuniones')->insert([
            $iglesiaCol          => 1,
            'fecha_primera_comunion' => '2026-02-15',
            $feligresCol         => 4,  // feligrés #4 comulgante
            $catequistaCol       => 1,  // feligrés #1 catequista
            $ministroCol         => 2,  // feligrés #2 ministro
            $parrocoCol          => 3,  // feligrés #3 párroco
            'libro_comunion'     => 'Libro C1',
            'folio'              => 'FC-01',
            'partida_numero'     => 'PC-0001',
            'observaciones'      => 'Primera comunión de prueba (seed).',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }
}