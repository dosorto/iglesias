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

        $ig  = Schema::hasColumn('bautismos', 'iglesia_id')   ? 'iglesia_id'   : 'id_iglesia';
        $enc = Schema::hasColumn('bautismos', 'encargado_id') ? 'encargado_id' : 'id_encargado';
        $bau = Schema::hasColumn('bautismos', 'bautizado_id') ? 'bautizado_id' : 'id_bautizado';
        $pad = Schema::hasColumn('bautismos', 'padre_id')     ? 'padre_id'     : 'id_padre';
        $mad = Schema::hasColumn('bautismos', 'madre_id')     ? 'madre_id'     : 'id_madre';
        $pio = Schema::hasColumn('bautismos', 'padrino_id')   ? 'padrino_id'   : 'id_padrino';
        $mna = Schema::hasColumn('bautismos', 'madrina_id')   ? 'madrina_id'   : 'id_madrina';

        $bautismos = [
            [
                $ig              => 1,
                'fecha_bautismo'  => '2026-01-08',
                $enc             => 1,
                $bau             => 4,
                $pad             => 1,
                $mad             => 2,
                $pio             => 3,
                $mna             => 6,
                'libro_bautismo'  => 'Libro 1',
                'folio'           => 'F-01',
                'partida_numero'  => 'P-0001',
                'lugar_nacimiento'=> 'Ciudad de Guatemala',
                'lugar_expedicion'=> 'Parroquia San Pedro',
                'fecha_expedicion'=> '2026-01-08',
                'observaciones'   => 'Bautismo ordinario.',
                'nota_marginal'   => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                $ig              => 1,
                'fecha_bautismo'  => '2026-01-22',
                $enc             => 1,
                $bau             => 5,
                $pad             => 3,
                $mad             => 6,
                $pio             => 7,
                $mna             => 8,
                'libro_bautismo'  => 'Libro 1',
                'folio'           => 'F-02',
                'partida_numero'  => 'P-0002',
                'lugar_nacimiento'=> 'Quetzaltenango',
                'lugar_expedicion'=> 'Parroquia San Pedro',
                'fecha_expedicion'=> '2026-01-22',
                'observaciones'   => 'Bautismo de urgencia.',
                'nota_marginal'   => 'Confirmar documentos.',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                $ig              => 1,
                'fecha_bautismo'  => '2026-02-05',
                $enc             => 1,
                $bau             => 7,
                $pad             => 2,
                $mad             => 4,
                $pio             => 1,
                $mna             => 3,
                'libro_bautismo'  => 'Libro 1',
                'folio'           => 'F-03',
                'partida_numero'  => 'P-0003',
                'lugar_nacimiento'=> 'Antigua Guatemala',
                'lugar_expedicion'=> 'Parroquia San Pedro',
                'fecha_expedicion'=> '2026-02-05',
                'observaciones'   => null,
                'nota_marginal'   => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                $ig              => 1,
                'fecha_bautismo'  => '2026-02-19',
                $enc             => 1,
                $bau             => 8,
                $pad             => 5,
                $mad             => 6,
                $pio             => 2,
                $mna             => 4,
                'libro_bautismo'  => 'Libro 1',
                'folio'           => 'F-04',
                'partida_numero'  => 'P-0004',
                'lugar_nacimiento'=> 'Escuintla',
                'lugar_expedicion'=> 'Parroquia San Pedro',
                'fecha_expedicion'=> '2026-02-19',
                'observaciones'   => 'Padres presentaron acta de nacimiento.',
                'nota_marginal'   => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                $ig              => 1,
                'fecha_bautismo'  => '2026-03-05',
                $enc             => 1,
                $bau             => 6,
                $pad             => 7,
                $mad             => 8,
                $pio             => 5,
                $mna             => 1,
                'libro_bautismo'  => 'Libro 2',
                'folio'           => 'F-01',
                'partida_numero'  => 'P-0005',
                'lugar_nacimiento'=> 'Cobán, Alta Verapaz',
                'lugar_expedicion'=> 'Parroquia San Pedro',
                'fecha_expedicion'=> '2026-03-05',
                'observaciones'   => 'Inicio del Libro 2.',
                'nota_marginal'   => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        DB::table('bautismos')->insert($bautismos);
    }
}