@php
    $paramsSelecteds = $quoteParameters->pluck('price','parameter_service_id')->all();
@endphp

@forelse ($parameters as $index => $parameter)
    @php 
            $paramSelected = array_key_exists($parameter->id, $paramsSelecteds) ?? false;
            if($paramSelected){
                $value      = $paramsSelecteds[$parameter->id];
            }else{
                $value      = $parameter->cost;
            }
    @endphp
    <label class="checkbox-inline mr-1">
    <input type="checkbox" data-click="related" data-format="1" data-key="{{$key}}" data-index="{{$index}}" data-related="{{$parameter->id}}" name="services[{{$key}}][parameter][{{$index}}][id]"  {{ $paramSelected ? 'checked': '' }} value="{{$parameter->id}}"> {{$parameter->item}}
    </label> $ 
    <input type="text" class="transparent-minified" data-format="1" data-key="{{$key}}" data-index="{{$index}}" data-related-value="{{$parameter->id}}" name="services[{{$key}}][parameter][{{$index}}][value]"  value="{{number_format($value, 0,'.',',')}}" {{ $paramSelected ? '': 'disabled' }}> | &nbsp;&nbsp; 


@empty
    No hay parámetros adicionales para este servicio.
@endforelse
