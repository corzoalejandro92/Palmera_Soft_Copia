@foreach ($documents as $document)
<tr style="opacity: {{ $document->anulado ? '0.8': '1' }}; {{ $document->anulado ? 'background-color: #ffdce8' : '' }}" >
    <td class="text-center">
        @if($document->sync)

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" style="height: 15px; width: 15px;">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
            </svg>
          
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="orange" style="height: 15px; width: 15px;">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
            </svg>
          
        @endif

        @if($document->anulado)

          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red" style="height: 15px; width: 15px;">
            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
          </svg>
          
          
        @endif


    </td>
    <td class="text-center"><b>{{ $document->documento}}</b> <br> <small>({{  $document->document->type->name }})</small> </td>
    <td class="text-center">{{ $document->numero}}</td>
    <td class="text-center"><b>{{ $document->fecha_grabacion}}</b>  <br> <small>{{  $document->hora_grabacion }}</small></td>
    <td>
        
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:30px;color:#c4cae4!important">Tipo</th>
                        <th style="width:30%;color:#c4cae4!important">Bodega</th>
                        <th style="width:35%;color:#c4cae4!important"> Producto</th>
                        <th style="color:#c4cae4!important">Cantidad</th>
                        <th style="color:#c4cae4!important">Lote</th>
                    </tr>
                </thead>
                <body>
                    @foreach ($document->details as $detail)
                    <tr>
                        <td>
                            @if($detail->tipoes == 'E')
                                <span class="badge badge-success"> E </span>
                            @elseif($detail->tipoes == 'S')
                            <span class="badge badge-danger"> S </span>
                            @endif
                        </td>
                        <td><b>{{ $detail->bodega }}</b> - <small>{{  optional($detail->warehouse)->name }}</small> </td>
                        <td><b>{{ $detail->producto }}</b> - <small>{{  optional($detail->product)->name }}</small></td>
                        <td><b>{{ $detail->cantidad }}</b>  <small>{{  $detail->unidad ? $detail->unidad : explode(' ', optional(optional($detail->product)->unit)->name)[0]  }} </small></td>
                        <td>{{ $detail->plot }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
    </td>
    <td style="width:200px;">
        <small>{{ $document->observation }} {{ $document->observation ? $document->updated_at->format('d/m/y h:i a') : '' }}</small>
    </td>
    
    <td>
        <div class="text-center">
            @if(!$document->anulado && $document->document->type->id != 1)
                <a href="#" data-click="openFormDelete" data-id="<?= $document->id ?>" data-detail="<?= $document->documento. ' '. $document->numero ?>" class="text-danger">
                    <i class="las la-trash la-2x"></i> Anular
                </a>
            @elseif($document->document->type->id == 1)   
                <small><i style="color: #a9a8a8">Las ordenes <br> de compra no <br>se pueden anular</i></small>
            @else
                <i>Anulado</i>
            @endif
        </div>
    </td>
</tr>
@endforeach