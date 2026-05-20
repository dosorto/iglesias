<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

if (config('backup.enabled', true)) {
    Schedule::command('backup:run')
        ->dailyAt((string) config('backup.time', '02:00'))
        ->withoutOverlapping();
}
