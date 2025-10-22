<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// -------------------------------------------------------------------------------------------------------------------
Artisan::command('state:initialized {--q|quiet}', function () {

    $silent = $this->option('quiet');
    $appKey = config('app.key');
    $initalized = $appKey !== null && $appKey !== '';

    if ($initalized) {
        if (! $silent) {
            $this->info('true');
        }

        return 0;
    }

    if (! $silent) {
        $this->info('false');
    }

    return 1;

})->purpose('Checks, if the application is initalized');

// -------------------------------------------------------------------------------------------------------------------
Artisan::command('cli:info {message}', function (string $message) {
    $this->components->info($message);
})->purpose('Display colored information message to the stdout');

// -------------------------------------------------------------------------------------------------------------------
Artisan::command('cli:error {message}', function (string $message) {
    $this->components->error($message);
})->purpose('Display colored error message to the stdout');
