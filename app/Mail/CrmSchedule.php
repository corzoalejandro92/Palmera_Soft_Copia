<?php

namespace App\Mail;

use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmSchedule extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $data, PDF $pdf)
    {
        $this->data = $data;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $element = $this->markdown('emails.crms.schedule')
        ->with('data', $this->data->input('body'))
        ->replyTo(Auth::User()->email, Auth::User()->name)
        ->subject($this->data->input('subjet'))
        ->attachData(
            $this->pdf->output(), 
            "cronograma-{$this->data->input('start_date_submit')}-{$this->data->input('end_date_submit')}.pdf",
            ['mime' => 'application/pdf']
            
        );

        if($this->data->filled('cc')){
            $element->cc($this->data->input('cc'));
        }

        if($this->data->filled('bcc')){
            $element->bcc($this->data->input('bcc'));
        }
        
        return $element;

    }
}
