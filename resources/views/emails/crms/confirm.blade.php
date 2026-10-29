@component('mail::message')
# Confirmación de servicios para el mes de {{ \Carbon\Carbon::now()->monthName }}
<p>
    {{ $data->contact->name}},<br><br>

    Esperamos se encuentre muy bien.
</p>

<p>
    Hemos enviado este correo porque estamos programando una nueva etapa de servicios para este mes y los siguientes servicios para {{ $data->contact->offices->first()->crm->name }} aun no cuentan con una fecha y hora sugerida por su parte
</p>
<p>
    <ul>
        @foreach ($data->schedules as $schedule)
            <li style="font-weight: bold;color: #3d4852;list-style: decimal;">{{ $schedule->service->name }} en sucursal {{ $schedule->office->name }}</li>
        @endforeach
    </ul>
</p>

<p>
    Estaremos atentos, nos gustaría recibir una respuesta a este correo detallando fecha y hora de cada uno de los servicios anteriormente listados, en su respectivo orden.
</p>

<p>
    Recuerde que las fechas que sugiera deben informarsen mínimamente con 2 días de anticipación y estarán sujetas a validaciones de disponibilidad técnica por parte de nuestro equipo de programación.
</p>

<p>
    
</p>

<p>
    Cordialmente,<br><br>
    {{ $user['name'] }}<br>
    Equipo de programación<br>
    {{ config('app.name') }}<br>
</p>

@slot('subcopy')
    Si recibes este correo y no tienes servicios con nosotros por favor responde a este correo para informarnos de este error para darte de baja en nuestra lista base de datos de programaciones. 
@endslot
@endcomponent
