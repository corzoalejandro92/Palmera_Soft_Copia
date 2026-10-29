<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->greeting('Hola '. $notifiable->name)
            ->subject('Notificación De Restablecimiento De Contraseña')
            ->line('Te hemos enviado este correo electrónico, porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta del sistema Palmera Junior')
            ->action('Restablecer Contraseña', url('').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false))
            ->line(Lang::getFromJson('Este enlace de restablecimiento de contraseña caducará en :count minutos.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna acción de tu parte, ya que tu acceso seguirá funcionando con la contraseña actualmente establecida.');
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
