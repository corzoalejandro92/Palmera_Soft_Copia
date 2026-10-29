<tr>
    <td style="text-align:center">
        <p>
            <b>{{$application->name}}</b>
        </p>
        <img class="avatar avatar-50" src="{{url($application->avatar ?? 'images/default/method.png')}}" style="
        height: 100px;
        width: 100px;
        object-fit: cover;
        border-radius: 9px;
    ">
        
    </td>
    <td style="text-align:center">{{$application->description}}</td>
</tr>