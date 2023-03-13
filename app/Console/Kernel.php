<?php

namespace App\Console;

use App\Libs\ManagerFile;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();
        $schedule->call(function (){
            Log::info("remove folder : export-file");
            ManagerFile::removeFolderLocal('export-file','export');
            ManagerFile::removeFolderLocal('zip-file','export');
            ManagerFile::removeFolderLocal(config('ressources-file.export'),'s3');
        })->everySixHours();
        $schedule->call(function (){
            Log::info("remove file : log.log");
            if(Storage::disk('log')->exists('logs/laravel.log')){
                 return  Storage::disk('log')->delete('logs/laravel.log');
            }
        })->saturdays();
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
