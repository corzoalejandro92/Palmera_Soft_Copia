{{-- @php 
    setlocale(LC_ALL, 'es_ES'); 
@endphp --}}
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th>#</th>
            <th>Ciudad</th>
            <th>Digitos</th>
            <th>Fecha</th>
            <th>Tipo Vehiculo</th>
            <th>Hora inicio</th>
            <th>Hora final</th>
            <th>Regla</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($peakPlates as $peakPlate)
        <tr >
            <td >{{ $peakPlate->id}}</td>
            <td >{{ $peakPlate->city->name}} - {{$peakPlate->city->state->name}}</td>
            <th >{{ implode(', ',$peakPlate->digits) }}</th>
            <td >{{ $peakPlate->date}}</td>
            <td >{{ $peakPlate->vehicleType->name}}</td>
            <td >{{ $peakPlate->start_time}}</td>
            <td >{{ $peakPlate->end_time}}</td>
            <td >{{ $peakPlate->rule->name}}</td>
            
            
            <td>
                <div class="text-center">
                    @can('peakPlate.destroy')
                        <a href="#" data-click="openFormDelete" data-id="{{ $peakPlate->id }}" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                    @can('peakPlate.edit')
                        <a href="#" data-click="openForm" data-id="{{ $peakPlate->id }}"  class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>