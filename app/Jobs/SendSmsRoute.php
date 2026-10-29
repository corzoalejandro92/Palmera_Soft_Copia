<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;

class SendSmsRoute implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $ch=curl_init();

        $post = array(
        'account' => '10019903', //número de usuario
        'apiKey' => 'gNgcr4Y84wWUQdQWG5nx2akTY7i0PZ', //clave API del usuario
        'token' => '7353113c1ebd881dc67b26ba0971a1bc', // Token de usuario
        'toNumber' => $this->data['phone'], //número de destino
        'sms' => $this->data['sms'] , // mensaje de texto
        'sc'=> '899775', //código corto para envío del mensaje de texto
        'request_dlvr_rcpt' => 0, //mensaje de texto con confirmación de entrega al celular
        );
        
        $url = "https://api101.hablame.co/api/sms/v2.1/send/"; //endPoint: Primario
        curl_setopt ($ch,CURLOPT_URL,$url) ;
        curl_setopt ($ch,CURLOPT_POST,1);
        curl_setopt ($ch,CURLOPT_POSTFIELDS, $post);
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt ($ch,CURLOPT_TIMEOUT, 20);
        $response= curl_exec($ch);
        curl_close($ch);
        $response= json_decode($response ,true) ;
        
        //La respuesta estará alojada en la variable $response
        
        if ($response["status"]== '1x000' ){
        dd( 'El SMS se ha enviado exitosamente con el ID: '.$response["smsId"].PHP_EOL);
        } else {
        dd( 'Ha ocurrido un error:'.$response["error_description"].'('.$response ["status" ]. ')'. PHP_EOL);
        }













        // //Realizar conexión con servicio SMS hablame.co
        // $client     = new Client([
        //     'headers' => ['Content-Type' => 'application/json'],
            
        // ]);
        // $url        = 'https://api101.hablame.co/';
        // $url2       = 'https://api102.hablame.co/';
        // $body       = [
        //     'account'       => '10019903',
        //     'apiKey'        => 'gNgcr4Y84wWUQdQWG5nx2akTY7i0PZ',
        //     'token'         => '7353113c1ebd881dc67b26ba0971a1bc',
        //     'toNumber'      => $this->data['phone'],
        //     'sms'           => $this->data['sms'],
        //     'flash'         => 1,
        //     'isPriority'    => 1,
        //     'sc'            => '899775'
        // ];

        // $response =  $client->request('POST',$url, ['form_params' => $body]);
        // dd($response);
        
    }
}
