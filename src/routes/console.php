<?php

use App\Console\Commands\Crawler\ImportNewsCommand;
use App\Console\Commands\Crawler\ImportOffersCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(ImportOffersCommand::class)->dailyAt('6:00')->withoutOverlapping();
Schedule::command(ImportNewsCommand::class)->dailyAt('7:00')->withoutOverlapping();
