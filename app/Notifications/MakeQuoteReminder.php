<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalFacade as Onesignal;
use Ramsey\Uuid\Uuid;

class MakeQuoteReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quote;
    protected $uuid;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($quote)
    {
        $this->uuid = Uuid::uuid4();
        $this->uuid .= '-' . strtotime(date('Y-m-d H:s:i'));
        $this->quote = $quote;
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

        return (new MailMessage)
        ->greeting($notifiable->name)
        ->subject('Agregar recordatorio a la cotización de ' .$this->quote->crm()->get()->last()->name)
        ->line('Queremos recordarte que acabas de crear una cotización en estado pendiente, es importante que crees un evento en la agenda que te recuerde cuando debes comunicarte nuevamente para conocer la retroalimentación del cliente.')
        ->action('Añadir evento a la agenda', route('sheduling.add-quote-event', ['quote' => $this->quote->id, 'user' => $notifiable->id, "notification" => $this->id]))
        ->line('Si ya creaste el evento respectivo a esta cotización, haz caso omiso a este correo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {

        OneSignal::sendNotificationToExternalUser(
            'Agregar recordatorio a la cotización de ' .$this->quote->crm()->get()->last()->name,
            "{$notifiable->id}",
            $url = route('sheduling.add-quote-event', [ 'quote' => $this->quote->id, 'user' => $notifiable->id, "notification" => $this->id]),
            $data = null,
            $buttons = null,
            $schedule = null
        );

        return [
            "time" => Carbon::now(),
            "user" => $notifiable,
            "quote" => $this->quote->makeHidden(['format_html']),
            "crm"   => $this->quote->crm()->get()->last()

        ];

    }

}
