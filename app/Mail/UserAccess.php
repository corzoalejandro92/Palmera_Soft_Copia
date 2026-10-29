<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAccess extends Mailable
{
    use Queueable, SerializesModels;

    private $user       = null;
    private $password   = null;
    private $greeting   = 'Buen Día';

    public function __construct($user, $password)
    {

        $this->user  = $user;
        $this->password = $password;

        $hour = \Carbon\Carbon::now()->format('H');                                 
        if ($hour < 12) $this->greeting =  'Buen Dia';
        if ($hour >= 12 && $hour < 18) $this->greeting =  'Buena Tarde';
        if ($hour >= 18) $this->greeting =  'Buena Noche';  
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $element = $this->markdown('emails.users.access')
        ->with(['user' => $this->user, 'password' => $this->password, 'greeting' => $this->greeting])
        ->subject("Datos de acceso a usuarios - Sistema Palmera Junior");

        return $element;
    }
}
