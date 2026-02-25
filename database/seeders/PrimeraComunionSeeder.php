<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrimeraComunionSeeder extends Seeder
{
    public function run(): void
    {
        $table = Schema::hasTable('primeras_comuniones') ? 'primeras_comuniones'
              : (Schema::hasTable('primeras_comunion') ? 'primeras_comunion' : null);

        if (!$table) return;

        $iglesiaCol  = Schema::hasColumn($table, 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $feligresCol = Schema::hasColumn($table, 'feligres_id') ? 'feligres_id' : 'id_feligres';

        $catequistaIdCol = Schema::hasColumn($table, 'catequista_id') ? 'catequista_id' : 'id_catequista';
        $ministroIdCol   = Schema::hasColumn($table, 'ministro_id') ? 'ministro_id' : 'id_ministro';
        $parrocoIdCol    = Schema::hasColumn($table, 'parroco_id') ? 'parroco_id' : 'id_parroco';

        DB::table($table)->insert([
            $iglesiaCol => 1,
            'fecha_primera_comunion' => '2026-02-15',
            $feligresCol => 3,

            'feligres_nombre' => 'Nombre (seed)',
            'fecha_nacimiento' => '2000-06-25',
            'nombre_papa' => 'Papá (seed)',
            'nombre_mama' => 'Mamá (seed)',

            'catequista_nombre' => 'Catequista Demo',
            $catequistaIdCol => 1, // persona #1

            'ministro_nombre' => 'Ministro Demo',
            $ministroIdCol => 2, // persona #2

            'parroco_nombre' => 'Párroco Demo',
            $parrocoIdCol => 3, // persona #3

            'libro_comunion' => 'Libro C1',
            'folio' => 'FC-01',
            'partida_numero' => 'PC-0001',
            'observaciones' => 'Primera comunión de prueba (seed).',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}