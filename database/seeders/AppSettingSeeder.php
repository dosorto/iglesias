<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        AppSetting::query()->updateOrCreate(
            ['id' => 1],
            ['company_name' => 'NekoTech']
        );
    }
}
