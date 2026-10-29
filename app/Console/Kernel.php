<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Laravelista\LumenVendorPublish\VendorPublishCommand::class,
        \App\Console\Commands\CrmCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       
        // tarea de revision clientes sin cronograma a la 01:00 AM
        $schedule->command('crm:cron')
            ->emailOutputOnFailure(config('app.mail_admin'))
            ->dailyAt('01:00');


        
        $schedule->call(function () {
            $now = \Carbon\Carbon::now();
            //eliminar permanentemente notificaciones con edad de mas de 15 dias
            DB::table('notifications')
            ->where('created_at', '<', $now->subDays(15))
            ->delete();

            //eliminar permanentemente registros de auditoria con mas de 3 meses de antiguedad
            DB::table('audits')
                ->where('created_at', '<', $now->subDays(90))
                ->delete();
        })->twiceDaily();
     
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
