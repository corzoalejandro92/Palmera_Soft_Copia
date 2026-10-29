<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class RequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $data = null;
    public $user = null;   


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.crms.confirm')
        //->from($this->user['email'], $this->user['name'])
        ->replyTo($this->user['email'], $this->user['name'])
        ->with('data', $this->data)
        ->with('user', $this->user)
        ->subject('Confirmación de servicios para el mes de '. Carbon::now()->monthName . ' | '. config('app.name'));

    }
}
