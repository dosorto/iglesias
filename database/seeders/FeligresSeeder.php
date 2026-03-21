<?php

namespace Database\Seeders;

use App\Models\Feligres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeligresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feligres::create([
            'id_persona'    => 1,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2020-01-15',
            
        ]);

        
        Feligres::create([
            'id_persona'    => 2,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2020-03-10',
            
        ]);

        Feligres::create([
            'id_persona'    => 3,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2019-06-22',
            
        ]);

        Feligres::create([
            'id_persona'    => 4,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2021-09-05',
            'estado'        => 'Activo',
        ]);

        Feligres::create([
            'id_persona'    => 5,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2018-12-01',
            
        ]);

        Feligres::create([
            'id_persona'    => 6,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2022-02-14',
            'estado'        => 'Activo',
        ]);

        Feligres::create([
            'id_persona'    => 7,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2017-07-30',
            
        ]);

        Feligres::create([
            'id_persona'    => 8,
            'id_iglesia'    => 1,
            'fecha_ingreso' => '2023-04-18',
            
        ]);


    }
}
