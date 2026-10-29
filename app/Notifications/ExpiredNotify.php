<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalFacade as Onesignal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ExpiredNotify extends Notification implements ShouldQueue
{
    use Queueable;

    public $offices = null;
    public $headquarter = null;   

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($offices, $headquarter)
    {
        $this->offices = $offices;
        $this->headquarter = $headquarter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

   /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {


        try{
            Carbon::setLocale(config('app.locale'));
            setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');

            
            $string = '<ul>';
            foreach ($this->offices as $key => $office) {
                $date = Carbon::parse($office->last_service->date)->isoFormat('MMMM Do \d\e\l YYYY');
                $string.= '<li style="margin: 10px 0px!important;"><b>'. (isset($office->crm->name) ? $office->crm->name : '').'</b><br>';
                $string.= 'sucursal: '. $office->name . '<br>';
                $string.= 'Dirección: '. $office->full_address .'<br>';
                $string.= 'Último servicio: '. $office->last_service->service->name . '<br>';
                $string.= 'Fecha: '. $date . '<br></li>';
            }
            $string .= '</ul>';

            // Configuración para fechas en español
            Carbon::setLocale(config('app.locale'));
            setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');

            $date = Carbon::now()->isoFormat('D \d\e MMMM \d\e\l YYYY');


            return (new MailMessage)
            ->greeting('Hola '.$notifiable->name)
            ->subject( count($this->offices).' sucursales de '.$notifiable->headquarter->name.' sin cronograma para ejecutar del '.$date)
            ->line('Hemos encontrado que las siguientes sucursales de clientes han completado su cronograma de actividades y a partir de ahora no tienen actividades pendientes por ejecutar:')
            ->line(new HtmlString($string))
            ->line("Es importante que sepas, que solo te notificaremos una vez por sucursal de cliente, es decir que si no extiendes el cronograma de alguna de estas sucursales de cliente o la pasas por alto, asumiremos que no es necesario alertar nuevamente en futuras listas. Esta notificación ha sido enviada a los Coordinadores MIP, Asistentes MIP y Programación de la sede ".$this->headquarter->name)
            ->action('Ver listado en el soft', route('crm.expiredNotify',[$this->id]));


        }catch(\Exception $e){
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        } 
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {

        try {
            OneSignal::sendNotificationToExternalUser(
                'Hemos encontrado ('.count($this->offices).') sucursales de '.$notifiable->headquarter->name.' que ya no tienen cronograma de actividades pendiente por ejecutar, haz clic para revisar',
                "{$notifiable->id}",
                route('crm.expiredNotify',[$this->id]),
                null,
                null,
                null
            );
    
            return [
                "time" => Carbon::now(),
                "user" => $notifiable,
                "offices" => $this->offices,
                "headquarter" => $this->headquarter
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        

    }
}
