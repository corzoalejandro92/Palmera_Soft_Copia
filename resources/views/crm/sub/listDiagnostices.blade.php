@php use Carbon\Carbon; @endphp

<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="car-header">
                <div class="row">
                    <div class="col-6">
                        <h2>Diagnosticos</h2>
                    </div>    
                    <div class="col-6">
                        @can('quotes.diagnosis')
                            <button type="submit" form="sendDiagnostiic" class="btn btn-success pull-right"   >Nuevo Diagnostico</button>
                            <form id="sendDiagnostiic" target="_blank" action="{{route('diagnostic.creatorUser')}}">
                                    <input type="hidden" name="office" id="OfficeO">
                            </form>  
                        @endcan  
                    </div>    
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body card-dashboard">
                    <table class="table table-striped table-bordered zero-configuration" id="tblList">
                        <thead>
                            <tr>
                              <th>#</th>
                              <th>Fecha creación</th>
                              <th>Creado por</th>
                              <th>Estado</th>
                              <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($diagnostices as $diagnostice)
                                <tr id="tr{{$diagnostice->id}}" class="pointer">
                                  <!-- id -->
                                  <td class="text-center font-weight-bolder text danger"  data-id="{{$diagnostice->id}}">
                                      {{$diagnostice->id}}
                                  </td>
                    
                                  <!-- fecha -->
                                  <td class="text-center font-weight-bolder"  data-id="{{$diagnostice->id}}">
                                      {{Carbon::parse($diagnostice->created_at)->format('d/m/Y')}}
                                  </td>
                                   <!-- Técnico-->
                                   <td   data-id="{{$diagnostice->id}}">
                                        <div class="col-12" style="display: flex;">
                                            <div style="display: inline-block;">
                                                <img class="avatar xxs mr-1" src="{{url($diagnostice->createdBy->avatar ?? '')}}" onerror="this.src='{{asset('images/default/user.jpg')}}'" />
                                            </div>
                                            <div style="display: inline-flex; vertical-align: middle;">
                                                <span style="position: relative;">{{mb_strtoupper($diagnostice->createdBy ? $diagnostice->createdBy->name:  '')  }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center font-weight-bolder"  data-id="{{$diagnostice->id}}">
                                        {{optional($diagnostice->status)->name ?? ''}}
                                    </td>

                                    <!-- botón de descargar -->
                                    <td >
                                        @can('quotes.diagnosis')
                                        <a class="btn btn-info btn-sm mb-1" href="{{route('diagnostic.creatorUser',[$diagnostice->id])}}" target="_blank"><i class="las la-edit"></i>Editar</a>
                                        <a class="btn btn-success btn-sm mb-1" href="{{ route('diagnostic.show',["id" => $diagnostice->id])}}" target="_blank"><i class="lasft-arrow-down"></i> Descargar</a>
                                        <a class="btn btn-danger btn-sm mb-1" data-click="deleteDiagnostic" data-id="{{$diagnostice->id}}" href="#"><i class="las la-trash-alt"></i> Eliminar</a>
                                        @endcan
                                        
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var office=document.getElementById('OfficesCustomer').value;
    document.getElementById('OfficeO').value=office;
</script>

