
@if($routes->count() > 0)
<div class="alert alert-warning" role="alert">
    <b>Se ha detectado una o mas rutas para la fecha y técnicos indicados, a continuación podrás señalar o no en cuales rutas incluir este servicio.</b>
    @foreach ($routes as $route)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="{{$route->id}}" id="defaultCheck{{$route->id}}" name="services[{{$service}}][routes][]" checked="checked">
        <label class="form-check-label" for="defaultCheck{{$route->id}}">
          Ruta {{\Carbon\Carbon::parse($route->date)->format('d/m/Y')}} de {{$route->user->name}}
        </label>
      </div>
    @endforeach
</div>
@endif