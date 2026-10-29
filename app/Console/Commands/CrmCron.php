<?php

namespace App\Console\Commands;

use App\Notifications\ExpiredNotify;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrmCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando comprueba los cronogramas que está a punto de expirar y genera una notificación al área encargada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        
 
        //consultar por offices de clientes por sedes
        //donde a la fecha no tenga servicios creados
        // y donde expired_notify == true

        $headquarters = \App\Headquarter::with(['offices' => function ($offices){
            $offices
            ->with('crm')
            ->has('schedules')
            ->has('crm')
            ->whereDoesntHave('schedules', function($schedule){
                $schedule->whereDate('date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
            })->where('expired_notify', true);
        }])->get();

        foreach ($headquarters as $headquarter) {

            $sporadic = []; $frequent = [];
            foreach ($headquarter->offices as $office){

                $office->last_service = $office->schedules()->with('service')->get()->last();

                //filtrar clientes exporádicos periocididades
                // 1 -unica vez, 13 -sin periodiciad 
                if(in_array($office->last_service->periodicity_id, [1,13])){
                    array_push($sporadic, $office);
                }else{
                    //clientes frecuentes
                    array_push($frequent, $office);
                }                
            }

            if(count($frequent) > 0){
                $users = \App\User::whereHas('roles', function ($roles){
                    $roles->whereIn('slug',['programming','boss_programming','mip_assistant','mip_coordinator']);
                })->where('headquarter_id',$headquarter->id)->get();

                if(count($users) > 0){

                    
                    foreach($users as $user){
                        
                        try {
                            $delay = now()->addSeconds(2);
                            $user->notify((new ExpiredNotify($frequent,$headquarter))->delay($delay));
                        } catch (\Exception $e) {
                            Log::channel('crmcron')->info("Error al enviar el correo a ". $user->email. ': '. $e->getMessage());
                        }
                    }

                    //posterior al envío, cambiar el expired_notify = 0 para no notificar la suc de nuevo
                    $officesCollect = collect($frequent);
                    \App\Office::whereIn('id',$officesCollect->pluck('id'))->update(["expired_notify" => false]);
                } 
            }
  
        }

    }
}
