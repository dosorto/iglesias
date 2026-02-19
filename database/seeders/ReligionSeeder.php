<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Religion;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Religion::create([
            'religion' => 'Catolica',
        ]);

        Religion::create([
            'religion' => 'Evangelica',
        ]);

        Religion::create([
            'religion' => 'Mormones',
        ]);

        Religion::create([
            'religion' => 'Testigos de jehova',
        ]);

    }
}
