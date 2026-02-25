<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('instructores')) return;

        $feligresCol = Schema::hasColumn('instructores', 'feligres_id') ? 'feligres_id' : 'id_feligres';

        DB::table('instructores')->insert([
            $feligresCol => 2, // feligrés #2
            'path_firma' => 'firmas/instructor_1.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}