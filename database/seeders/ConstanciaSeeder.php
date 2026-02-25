<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConstanciaSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('constancias')) return;

        $iglesiaCol  = Schema::hasColumn('constancias', 'iglesia_id') ? 'iglesia_id' : 'id_iglesia';
        $feligresCol = Schema::hasColumn('constancias', 'feligres_id') ? 'feligres_id' : 'id_feligres';

        DB::table('constancias')->insert([
            $iglesiaCol => 1,
            $feligresCol => 4,
            'tipo_documento' => 'BAUTISMO',
            'folio' => 'C-0001',
            'fecha_emision' => now(),
            'estado' => 'VIGENTE',
            'motivo_anulacion' => null,
            'hash_code' => 'HASH-DEMO-0001',
            'path_pdf' => 'pdf/constancias/constancia_0001.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}