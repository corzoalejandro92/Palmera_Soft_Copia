<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalFacade as Onesignal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApprovedQuoteCopy extends Notification implements ShouldQueue
{
    use Queueable;

    public $quote = null;
    public $cc = null;   

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($quote, $cc)
    {
        $this->quote = $quote;
        $this->cc = $cc;
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

        /** Se recomienda migrar esta funcion por colas de laravels y cronjobs en el servidor
         * ref https://sendgrid.com/
         */

        try{
            if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
                sleep(1); //use usleep(500000) for half a second or less
            }
    
            if(env('MAIL_HOST', false) == 'mail.palmerajunior.com'){
                sleep(1); //use usleep(500000) for half a second or less
            }
    
            return (new MailMessage)
            ->cc($this->cc)
            ->greeting('¡Nueva Cotizacion Aprobada!')
            ->subject('¡Nueva cotización aprobada!')
            ->line('La cotización #'.$this->quote->code.' del cliente '.$this->quote->crm()->first()->name.' para la sucursal '.$this->quote->office()->first()->name.' realizada por '.$notifiable->name.' ha sido aprobada, consulta todos los detalles de esta cotización en el sistema.')
            ->action('Ver listado de cotizaciones', route('quotes.index').'?search='.$this->quote->code)
            ->line('Esta notificación es enviada a todos los Coordinadores MIP, Asistentes MIP y Programación de la sede de Palmera Junior de '. $this->quote->headquarter()->get()->last()->name);
        }catch(\Exception $e){
            Log::error($e->getMessage());
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
                '¡Nueva cotización aprobada! La cotización '.$this->quote->code.' del cliente '.$this->quote->crm()->get()->last()->name.' para la sucursal '.$this->quote->office()->first()->name.' ha sido aprobada',
                "{$notifiable->id}",
                route('quotes.index').'?search='.$this->quote->code,
                null,
                null,
                null
            );
    
            return [
                "time" => Carbon::now(),
                "user" => $notifiable,
                "quote" => $this->quote,
                "crm"   => $this->quote->crm()->first(),
                "office"   => $this->quote->crm()->first()
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        

    }
}
