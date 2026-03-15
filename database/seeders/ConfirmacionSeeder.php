<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfirmacionSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('confirmaciones')) return;

        $ig  = Schema::hasColumn('confirmaciones', 'iglesia_id')  ? 'iglesia_id'  : 'id_iglesia';
        $fel = Schema::hasColumn('confirmaciones', 'feligres_id') ? 'feligres_id' : 'id_feligres';
        $pad = Schema::hasColumn('confirmaciones', 'padre_id')    ? 'padre_id'    : 'id_padre';
        $mad = Schema::hasColumn('confirmaciones', 'madre_id')    ? 'madre_id'    : 'id_madre';
        $pio = Schema::hasColumn('confirmaciones', 'padrino_id')  ? 'padrino_id'  : 'id_padrino';
        $mna = Schema::hasColumn('confirmaciones', 'madrina_id')  ? 'madrina_id'  : 'id_madrina';
        $min = Schema::hasColumn('confirmaciones', 'ministro_id') ? 'ministro_id' : 'id_ministro';

        $confirmaciones = [
            [
                $ig              => 1,
                'fecha_confirmacion'  => '2026-01-10',
                'lugar_confirmacion'  => 'Parroquia San Pedro',
                $fel             => 4,
                $pad             => 1,
                $mad             => 2,
                $pio             => 3,
                $mna             => 6,
                $min             => 7,
                'libro_confirmacion'  => 'Libro 1',
                'folio'               => 'F-01',
                'partida_numero'      => 'P-0001',
                'lugar_nacimiento'    => 'Ciudad de Guatemala',
                'lugar_expedicion'    => 'Parroquia San Pedro',
                'fecha_expedicion'    => '2026-01-10',
                'observaciones'       => 'Confirmación ordinaria.',
                'nota_marginal'       => null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                $ig              => 1,
                'fecha_confirmacion'  => '2026-01-24',
                'lugar_confirmacion'  => 'Parroquia San Pedro',
                $fel             => 5,
                $pad             => 3,
                $mad             => 6,
                $pio             => 7,
                $mna             => 8,
                $min             => 7,
                'libro_confirmacion'  => 'Libro 1',
                'folio'               => 'F-02',
                'partida_numero'      => 'P-0002',
                'lugar_nacimiento'    => 'Quetzaltenango',
                'lugar_expedicion'    => 'Parroquia San Pedro',
                'fecha_expedicion'    => '2026-01-24',
                'observaciones'       => 'Confirmar documentos.',
                'nota_marginal'       => 'Pendiente acta de bautismo.',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                $ig              => 1,
                'fecha_confirmacion'  => '2026-02-07',
                'lugar_confirmacion'  => 'Parroquia San Pedro',
                $fel             => 7,
                $pad             => 2,
                $mad             => 4,
                $pio             => 1,
                $mna             => 3,
                $min             => 7,
                'libro_confirmacion'  => 'Libro 1',
                'folio'               => 'F-03',
                'partida_numero'      => 'P-0003',
                'lugar_nacimiento'    => 'Antigua Guatemala',
                'lugar_expedicion'    => 'Parroquia San Pedro',
                'fecha_expedicion'    => '2026-02-07',
                'observaciones'       => null,
                'nota_marginal'       => null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        DB::table('confirmaciones')->insert($confirmaciones);
    }
}