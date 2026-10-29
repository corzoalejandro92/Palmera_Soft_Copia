<?php

namespace App\Mail;

use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteSend extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $path;
    private $technical;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data, $pdf, $file = true)
    {
        $this->data           = $data;
        $this->path      = $pdf;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
   
        $element = $this->markdown('emails.route.send')
        ->with('data', $this->data['body'])
        ->replyTo(Auth::User()->email, Auth::User()->name)
        ->subject($this->data['subject'])
        ->attach(  storage_path("app/public/{$this->path}"),[
                'as' => $this->data['subject'].'.pdf',
                'mime' => 'application/pdf']);


        if(isset($this->data['cc'])){
            $element->cc($this->data['cc']);
        }

        if(isset($this->data['bcc'])){
            $element->cc($this->data['bcc']);
        }
        
        return $element;

    }
}
