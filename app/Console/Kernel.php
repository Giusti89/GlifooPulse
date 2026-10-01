<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('analytics:aggregate-visits --days=1')
            ->dailyAt('03:00')
            ->withoutOverlapping();

        $schedule->command('analytics:aggregate-social-clicks --days=1')
            ->dailyAt('03:10')
            ->withoutOverlapping();

        $schedule->command('analytics:purge --months=12')
            ->monthlyOn(1, '04:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
