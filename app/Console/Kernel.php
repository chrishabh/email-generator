<?php

namespace App\Console;

use App\Models\CronTiming;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
    
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     protected $commands = [
        \App\Console\Commands\VerificationActionRequired::class,
     ];
    protected function schedule(Schedule $schedule)
    {
        $Verification_action = CronTiming::getCronTiming('verification_action');
        if (!empty($Verification_action)) {
            $filePath = storage_path() . '/logs/batchLogs/VerificationActionRequired.log';
            if (!file_exists($filePath)) {
                File::put($filePath, '');
            }
            $schedule->command('VerificationActionRequired')->cron($Verification_action->cron_timing)->appendOutputTo($filePath);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
