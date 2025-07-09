<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        Commands\GenerateRecurringSchedules::class,
    ];
    
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('schedules:generate')->weekly(); // Jalankan setiap minggu
        $schedule->command('bookings:cancel-expired')->everyMinute();
    }
    
}
