
<div class="infestations">
    <hr>
    @if($users)
        <h6>Técnicos</h6>
        <div>
            <div class="row">
                <div class="col-12">
                    @foreach($users as $user)
                        <div id="userDiv{{$user->id}}" class="d-inline-block">
                            &nbsp;<img class="avatar xxs" src="<?= url($user->full_avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" /> <span>&nbsp;<?= $user->name ?></span> <span class="times" data-click="removeUser" data-id="{{$user->id}}">&nbsp;<i class="las la-times-circle la-2x"> </i></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <i class="text-center display-block">No has seleccionado una disponibilidad técnica</i>
    @endif
    <hr>
    <select class="form-control display-hidden" name="users[]" multiple>
        @if($users)
            @foreach($users as $user)
                <option value="{{$user->id}}" selected>{{$user->name}}</option>
            @endforeach
        @endif
    </select>
</div>
