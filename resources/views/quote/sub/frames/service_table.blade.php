<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><strong>SERVICIO</strong></td>
			<td><strong>CONTROLES</strong></td>
			<td><strong>PRECIO</strong></td>
		</tr>
        @foreach ($quote->services as $service)
            <tr>
                <td>{{$service->service->name}}</td>
                <td>{{$service->infestations->implode('infestation.name',', ')}}</td>
                <td>{{number_format($service->price,0,'.',',')}}</td>
            </tr>
        @endforeach
	</tbody>
</table>

<p>Precio total de la cotización ${{number_format($quote->total,0,'.',',')}}</p>