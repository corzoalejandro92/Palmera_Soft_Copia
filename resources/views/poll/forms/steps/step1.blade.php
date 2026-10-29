<h6><i class="step-icon la ft-align-left"></i>Nombre</h6>
<fieldset class="fieldspace">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
            <h5>Titulo</h5>
        <input class="form-control" type="text" name="title" value="{{$poll->title ?? '' }}">
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <h5>Descripción</h5>
            <textarea id="summernote" name="observation">{{$poll->observation ?? '' }}</textarea>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <br>
            <h5>Habilitar esta encuesta</h5>
            <div class="form-check" style="margin-left: 15px;">
                <input class="form-check-input big-checkbox" type="checkbox" value="1" id="enabled" name="enabled" {{ $poll->enabled ? 'checked': ''}}>
                <label class="form-check-label" for="enabled">
                    &nbsp;Encuesta habilitada 
                </label>
                <small style="display: block;margin-top: 8px;"> <i class="la la-info"></i> Si habilitas esta opción para esta encuesta, cualquier otra encuesta que se encuentre habilitada pasará a inactivarse, el software y el app de técnicos solo soportan una encuesta activa a la vez</small>
              </div>
        </div>

    </div>
</fieldset>