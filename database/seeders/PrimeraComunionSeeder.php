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

        $ig  = Schema::hasColumn('primeras_comuniones', 'iglesia_id')    ? 'iglesia_id'    : 'id_iglesia';
        $fel = Schema::hasColumn('primeras_comuniones', 'feligres_id')   ? 'feligres_id'   : 'id_feligres';
        $cat = Schema::hasColumn('primeras_comuniones', 'catequista_id') ? 'catequista_id' : 'id_catequista';
        $min = Schema::hasColumn('primeras_comuniones', 'ministro_id')   ? 'ministro_id'   : 'id_ministro';
        $par = Schema::hasColumn('primeras_comuniones', 'parroco_id')    ? 'parroco_id'    : 'id_parroco';

        $hasCertFields = Schema::hasColumn('primeras_comuniones', 'fecha_expedicion');

        $comuniones = [
            [
                $ig                      => 1,
                'fecha_primera_comunion' => '2026-02-15',
                $fel                     => 4,
                $cat                     => 1,
                $min                     => 2,
                $par                     => 3,
                'libro_comunion'         => 'Libro C1',
                'folio'                  => 'FC-01',
                'partida_numero'         => 'PC-0001',
                'observaciones'          => 'Primera comunión grupo enero-febrero.',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                $ig                      => 1,
                'fecha_primera_comunion' => '2026-02-15',
                $fel                     => 5,
                $cat                     => 1,
                $min                     => 2,
                $par                     => 3,
                'libro_comunion'         => 'Libro C1',
                'folio'                  => 'FC-02',
                'partida_numero'         => 'PC-0002',
                'observaciones'          => null,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                $ig                      => 1,
                'fecha_primera_comunion' => '2026-03-01',
                $fel                     => 7,
                $cat                     => 1,
                $min                     => 2,
                $par                     => 3,
                'libro_comunion'         => 'Libro C1',
                'folio'                  => 'FC-03',
                'partida_numero'         => 'PC-0003',
                'observaciones'          => 'Grupo marzo.',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                $ig                      => 1,
                'fecha_primera_comunion' => '2026-03-01',
                $fel                     => 8,
                $cat                     => 1,
                $min                     => 2,
                $par                     => 3,
                'libro_comunion'         => 'Libro C1',
                'folio'                  => 'FC-04',
                'partida_numero'         => 'PC-0004',
                'observaciones'          => null,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                $ig                      => 1,
                'fecha_primera_comunion' => '2026-03-22',
                $fel                     => 6,
                $cat                     => 1,
                $min                     => 2,
                $par                     => 3,
                'libro_comunion'         => 'Libro C1',
                'folio'                  => 'FC-05',
                'partida_numero'         => 'PC-0005',
                'observaciones'          => 'Celebración en capilla filial.',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ];

        if ($hasCertFields) {
            $certData = [
                ['lugar_celebracion' => 'Parroquia San Pedro', 'lugar_expedicion' => 'Parroquia San Pedro', 'fecha_expedicion' => '2026-02-15', 'nota_marginal' => null],
                ['lugar_celebracion' => 'Parroquia San Pedro', 'lugar_expedicion' => 'Parroquia San Pedro', 'fecha_expedicion' => '2026-02-15', 'nota_marginal' => null],
                ['lugar_celebracion' => 'Parroquia San Pedro', 'lugar_expedicion' => 'Parroquia San Pedro', 'fecha_expedicion' => '2026-03-01', 'nota_marginal' => null],
                ['lugar_celebracion' => 'Parroquia San Pedro', 'lugar_expedicion' => 'Parroquia San Pedro', 'fecha_expedicion' => '2026-03-01', 'nota_marginal' => 'Pendiente acta de nacimiento.'],
                ['lugar_celebracion' => 'Capilla Santa Rosa',  'lugar_expedicion' => 'Parroquia San Pedro', 'fecha_expedicion' => '2026-03-22', 'nota_marginal' => null],
            ];
            foreach ($comuniones as $i => &$row) {
                $row = array_merge($row, $certData[$i]);
            }
            unset($row);
        }

        DB::table('primeras_comuniones')->insert($comuniones);
    }
}