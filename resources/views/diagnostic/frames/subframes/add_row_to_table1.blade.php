<tr>
    <td style="text-align:center">
        <p>
            <b>{{$infestation->name}}</b>
        </p>
        <img class="avatar avatar-50" src="{{url($infestation->avatar ?? 'images/default/plaga.png')}}" style="
        height: 100px;
        width: 100px;
        object-fit: cover;
        border-radius: 9px;
    ">
        
    </td>
    <td style="text-align:center">{{$infestation->description}}</td>
    <td style="text-align:center"></td>
    <td style="text-align:center"></td>
    <td style="text-align:center"></td>
    <td style="text-align:center"></td>
    <td style="text-align:center">{{$infestation->causes}}</td>
</tr>