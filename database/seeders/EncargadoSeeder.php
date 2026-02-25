<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EncargadoSeeder extends Seeder
{
    public function run(): void
    {
        $table = Schema::hasTable('encargados') ? 'encargados' : (Schema::hasTable('encargado') ? 'encargado' : null);
        if (!$table) return;

        $feligresCol = Schema::hasColumn($table, 'feligres_id') ? 'feligres_id' : 'id_feligres';

        DB::table($table)->insert([
            $feligresCol => 1, // feligrés #1
            (Schema::hasColumn($table,'path_firma_principal') ? 'path_firma_principal' : 'path_firma_principal') => 'firmas/encargado_principal.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}