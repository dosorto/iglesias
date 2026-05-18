<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Religion;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religiones = [
            ['religion' => 'Católica'],
            ['religion' => 'Evangélica'],
        ];

        foreach ($religiones as $r) {
            Religion::firstOrCreate(['religion' => $r['religion']]);
        }
    }
}
