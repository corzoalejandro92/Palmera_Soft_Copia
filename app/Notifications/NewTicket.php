<?php

namespace App\Notifications;

use App\TicketQuestion;
use Illuminate\Support\Facades\Lang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalFacade as Onesignal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NewTicket extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $ticket;
    public $cc;   

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TicketQuestion $ticket, $cc=null)
    {
        $this->ticket = $ticket;
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
        
        $email = (new MailMessage)
            ->cc($this->cc)
            ->greeting('¡Nuevo ticket creado!')
            ->subject('¡Nuevo ticket creado!')
            ->line('El ticket # '.$this->ticket->id.' del cliente '.$this->ticket->office->crm->name.' para la sucursal '.$this->ticket->office->name.', de prioridad '.$this->ticket->priority->name .'; ha sido creado, para mas informacion has clic al boton Ver tickets.')
            ->line('Nombre: '.$this->ticket->registered->name)
            ->line('Titulo: '.$this->ticket->subject)
            ->line('Mensaje: '.$this->ticket->message);
            
        if(!$notifiable->roles()->pluck('slug')->contains('customer')) $email->action('Ver tickets', route('tickets.tickets'));
        
        return $email;
            
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
                '¡Nuevo ticket creado! El ticket # '.$this->ticket->id.' del cliente '.$this->ticket->office->crm->name.'  para la sucursal '.$this->ticket->office->name.' ha sido creado.',
                "{$notifiable->id}",
                route('tickets.tickets'),
                null,
                null,
                null
            );
    
            return [
                "time" => Carbon::now(),
                "user" => $notifiable,
                "registered" => $this->ticket->registered,
                "ticket" => $this->ticket,
                "crm"   => $this->ticket->office->crm,
                "office"   => $this->ticket->office
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        

    }
}
