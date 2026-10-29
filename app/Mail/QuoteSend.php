<?php

namespace App\Mail;

use App\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuoteSend extends Mailable
{
    use Queueable, SerializesModels;


    private $data;
    private $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $data, Quote $quote)
    {
        $this->data = $data;
        $this->quote = $quote;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $element = $this->markdown('emails.quote.send')
        //->from($this->quote->commercial->email, $this->quote->commercial->name)
        ->replyTo($this->quote->commercial->email, $this->quote->commercial->name)
        ->with('data', $this->data->input('body'))
        ->subject($this->data->input('subjet'))
        ->attach( Storage::disk('public')->path($this->quote->file->path), ["as" => $this->quote->file->name]);

        if($this->data->filled('cc')){
            $element->cc($this->data->input('cc'));
        }

        if($this->data->filled('bcc')){
            $element->bcc($this->data->input('bcc'));
        }
        
        return $element;

    }
}
