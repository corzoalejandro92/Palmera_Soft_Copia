<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ReportService extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, String $url)
    {
        $this->data = $data;
        $this->url = $url;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        sleep(1); //dormir un segundo para no hacer envíos masivos y caer en spam

        $element = $this->markdown('emails.crms.report')
        ->with('data', $this->data )
        ->subject('Reporte de servicio #'.$this->data->schedule->id. ' - '. $this->data->schedule->service->name)
        ->attach(
            Storage::disk('public')->path($this->url), 
            [
              'as'=>  "Reporte de servicio #{$this->data->schedule->id}.pdf",
              'mime' => 'application/pdf'
            ]            
        );

        if(isset($this->data->cc)){
            $element->cc($this->data->cc);
        }

        if(isset($this->data->bcc)){
            $element->bcc($this->data->bcc);
        }
        
        return $element;

    }
}
