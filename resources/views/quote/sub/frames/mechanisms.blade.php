<br>
@foreach ($products as $product)

<br />
<img src="data:image/jpeg;base64,{{$product->base64}}" style="float:left; height:50px; margin-left:4px; margin-right:4px; width:50px" />{{$product->name}}:<br />
{{$product->description}}<br />

@endforeach