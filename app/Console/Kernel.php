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
        Commands\PayoutCommand::class,
        Commands\IcalCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->payout($schedule);
        $this->ical($schedule);
    }

    private function payout($schedule)
    {
        $date = (int)env('PAYOUT_DATE', 15);
        $time = env('PAYOUT_TIME', '15:00');
        $schedule->command('payout:calculate')->monthlyOn($date, $time);
    }

    private function ical($schedule){
        $type = env('ICAL_TYPE', 'hour');
        $hour = (int) env('ICAL_HOUR', 0);
        $minute = (int) env('ICAL_MINUTE', 0);
        if($type == 'hour'){
            if($hour > 1){
                $schedule->command('ical:import')->cron('0 0/'.$hour. ' * * *');
            }else{
                $schedule->command('ical:import')->cron('0 * * * *');
            }
        }else{
            if($minute > 1){
                $schedule->command('ical:import')->cron('0/'.$minute. ' * * * *');
            }else{
                $schedule->command('ical:import')->cron('* * * * *');
            }
        }
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
