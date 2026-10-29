
<h6><i class="step-icon ft-calendar"></i>Fecha y horario</h6>
<fieldset class="fieldspace">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2">
            <h5>Dia de la ruta a programar:</h5>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-prepend" >
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control datepicker pickadate-translations" style="width: 80%;" name="init_date" type="date" placeholder="A partir del"  data-change="hiden-error">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
            <hr>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12">
            <h5>Técnicos a programar</h5>
            <select class="form-control select2" name="manual_users[]" multiple>
                @if(isset($users))
                    @foreach($users as $user)
                        <option value="{{$user->id}}" selected>{{$user->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>

    </div>

</fieldset>