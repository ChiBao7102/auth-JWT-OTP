<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function schedule(Schedule $schedule): void
    {
        //Run at 00:00 everyday
        $schedule->command('remove-user-expired-register')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        include base_path('routes/console.php');
    }
}
