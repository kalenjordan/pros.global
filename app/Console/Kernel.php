<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notifications:send --limit=3 --live')
            ->hourly()
            ->appendOutputTo(storage_path('logs/cron.log'));

        $schedule->command('sitemap:generate --path=/home/forge/pros.global/static/sitemap.xml')
            ->daily()
            ->appendOutputTo(storage_path('logs/cron.log'));

        $schedule->command('algolia:index --limit=999')
            ->daily()
            ->appendOutputTo(storage_path('logs/cron.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
