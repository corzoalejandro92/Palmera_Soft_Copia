@component('mail::message')
# Acceso Usuarios al Sistema Palmera Junior

<p>
    {{$greeting}} {{ explode(' ', $user->name)[0]}},<br><br>

    Esperamos que te encuentres muy bien.
</p>

<p>
    Te hacemos entrega de los datos de acceso al sistema, con los cuales podrás
    gestionar toda la información de Palmera Junior correspondiente a tus funciones asignadas.

<p>
    <b> Usuario: </b><span>{{$user->email}}</span><br>
    <b> Contraseña: </b><span>{{$password}}</span><br>
    @component('mail::button', ['url' => route('login'), 'color' => 'btn-block btn-glow btn-bg-gradient-x-green-green col-12 mr-1 mb-1'])
        Acceder al sistema
    @endcomponent
</p>

<p>
    Es importante que guardes estos datos en un lugar seguro. Cuando accedas podrás
    cambiar la contraseña por algo mas sencillo de recordar, desde las opciones de tu 
    perfil.
</p>

<p>
    Cordialmente,<br>
    {{ config('app.name') }}
</p>

@slot('subcopy')
    Si tienes problemas para hacer clic en el botón Acceder al sistema, copie y pegue la URL 
    dentro de su navegador web: {{route('login')}}
@endslot
@endcomponent
