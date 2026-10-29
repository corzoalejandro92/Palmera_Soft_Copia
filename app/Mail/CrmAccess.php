<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrmAccess extends Mailable
{
    use Queueable, SerializesModels;

    private $contact    = null;
    private $crm        = null;
    private $password   = null;
    private $greeting   = 'Buen Día';

    public function __construct($crm, $contact, $password)
    {
        $this->crm      = $crm;
        $this->contact  = $contact;
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
        $element = $this->markdown('emails.crms.access')
        ->with(['contact' => $this->contact, 'crm' => $this->crm, 'password' => $this->password, 'greeting' => $this->greeting])
        ->subject("Datos de acceso a clientes - Sistema Palmera Junior");

        return $element;
    }
}
