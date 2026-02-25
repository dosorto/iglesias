<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmisionDetalleSeeder extends Seeder
{
    public function run(): void
    {
        $table = Schema::hasTable('emision_detalle') ? 'emision_detalle' : (Schema::hasTable('emision_detalles') ? 'emision_detalles' : null);
        if (!$table) return;

        $emisionCol = Schema::hasColumn($table, 'emision_id') ? 'emision_id' : 'id_emision';

        DB::table($table)->insert([
            $emisionCol => 1, // constancia #1
            'referencia_tipo' => 'BAUTISMO',
            'referencia_id' => 1, // bautismo #1
            'notas' => 'Detalle de emisión de prueba (seed).',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}