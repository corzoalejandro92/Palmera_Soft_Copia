<?php

namespace App\Notifications;

use App\TicketAnswer;
use App\TicketQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalFacade as Onesignal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AnswerTicket extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $ticket;
    public $answer;
    public $cc;   

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TicketQuestion $ticket, $cc=null,TicketAnswer $answer)
    {
        $this->ticket = $ticket;
        $this->answer = $answer;
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
            ->greeting('¡Nueva respuesta de ticket!')
            ->subject('¡Nueva respuesta de ticket!')
            ->line('El ticket # '.$this->ticket->id.' del cliente '.$this->ticket->office->crm->name.' para la sucursal '.$this->ticket->office->name.' ha sido respondido.')
            ->line('Nombre: '.$this->answer->registered->name)
            ->line('Titulo: '.$this->ticket->subject)
            ->line('Mensaje: '.$this->answer->message);
            
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
                '¡Nueva respuesta de ticket! El ticket # '.$this->ticket->id.' del cliente '.$this->ticket->office->crm->name.'  para la sucursal '.$this->ticket->office->name.' ha tiene una nueva respuesta.',
                "{$notifiable->id}",
                route('tickets.tickets'),
                null,
                null,
                null
            );
    
            return [
                "time" => Carbon::now(),
                "user" => $notifiable,
                "registered" => $this->answer->registered,
                "ticket" => $this->ticket,
                "answer" => $this->answer,
                "crm"   => $this->ticket->office->crm,
                "office"   => $this->ticket->office
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        

    }
}
