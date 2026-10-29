@component('mail::message')
# Reporte de servicio {{$data->schedule->service->name}}

<p>
    {{$data->greeting}} {{ explode(' ', $data->contact->name)[0]}},<br><br>

    Esperamos que te encuentres muy bien.
</p>

<p>
    En este correo encontrarás adjunto el reporte de servicio {{$data->schedule->service->name}} que fue realizado el día {{$data->schedule->date}} en las instalaciones {{$data->schedule->crm->name}} - {{$data->schedule->office->name}}
</p>

<p>
    Recuerda, que tambien podrás consultar tus reportes de servicio desde nuestra app para clientes, la cual podrás descargar en Playstore y Appstore, o desde nuestro sistema web. Si aun no tienes acceso a tu cuenta de cliente, solicítala al comercial que cotizó tus servicios.
</p>

<p>
    Cordialmente,<br>
    {{ config('app.name') }}
</p>

@slot('subcopy')
    Si tienes problemas para descargar este documento, ponte en contacto con nosotros por medio de nuestra plataforma de tickets en el app de clientes o en el sistema web. <br><br>
    Recuerda que este correo se envía de forma automática y que cualquier respuesta  que realice sobre este llegará a un buzón sin supervisión. 
@endslot
@endcomponent
