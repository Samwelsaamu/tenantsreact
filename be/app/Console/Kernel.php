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
        Commands\MonthlyUpdates::class,
        Commands\DatabaseBackUps::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sanctum:prune-expired --minutes=3')->everyMinute();
        // $schedule->command('backup:clean')->everyMinute();
        $schedule->command('backup:run')->everyMinute();
        // $schedule->command('backup:monitor')->everyMinute();
        // $schedule->command('database:backup')->everyMinute();
        // ->monthlyOn(1,'00:50') setting every Month on 1st at 12:50 am
        $schedule->command('monthlybills:update')
                    ->monthlyOn(1,'00:50')
                    ->timezone('Africa/Nairobi');
        
    }

    /**
     * Register the commands for the application.
     *
     * @return voidk
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
