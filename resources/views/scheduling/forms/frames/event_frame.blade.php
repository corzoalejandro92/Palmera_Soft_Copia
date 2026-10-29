
@if(isset($start_hour) && $start_hour && !$scheduling )

    @php 
        $start_date = \Jenssegers\Date\Date::parse($start_hour)->format('d F, Y');
        $end_date = \Jenssegers\Date\Date::parse($end_hour)->format('d F, Y');
    @endphp


    <div class="col-6" style="display: inline-table;">
    <label class="col-form-label" style="display: block;">Fecha y hora inicio*</label>
    <input data-change="hiden-error" class="typeahead form-control pickadate-translations" style="width: 60%;display: inline-block;background-color: #3a7f56!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{ucwords($start_date)}}" name="start"  placeholder="Fecha" autocomplete="no">
    <input data-change="hiden-error" type="text" class="form-control timepickers" style="width: 40%;display: inline-block;background-color: #4CAF50!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{strtoupper($start_hour->format('h:i a')) }}" name="time_start" placeholder="Hora" autocomplete="no">
    </div>

    <div class="col-6" style="display: inline-table;">
    <label class="col-form-label" style="display: block;">Fecha y hora fin*</label>
    <input data-change="hiden-error" class="typeahead form-control pickadate-translations" style="width: 60%;display: inline-block;background-color: #3a7f56!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{ucwords($end_date)}}" name="end"  placeholder="Fecha" autocomplete="no">
    <input data-change="hiden-error" type="text" class="form-control timepickers" style="width: 40%;display: inline-block;background-color: #4CAF50!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{strtoupper($end_hour->format('h:i a')) }}" name="time_end" placeholder="Hora" autocomplete="no">
    </div>

@else
    <div class="col-6" style="display: inline-table;">
        <label class="col-form-label" style="display: block;">Fecha y hora inicio*</label>
        <input data-change="hiden-error" class="typeahead form-control pickadate-translations" style="width: 60%;display: inline-block;background-color: #3a7f56!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{ isset ($scheduling->start_front) ? ucwords($scheduling->start_front->format('d F, Y')) : '' }}" name="start"  placeholder="Fecha" autocomplete="no">
        <input data-change="hiden-error" type="text" class="form-control timepickers" style="width: 40%;display: inline-block;background-color: #4CAF50!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{isset ($scheduling->start_front) ? strtoupper($scheduling->start_front->format('h:i a')) : '' }}" name="time_start" placeholder="Hora" autocomplete="no">
    </div>

    <div class="col-6" style="display: inline-table;">
        <label class="col-form-label" style="display: block;">Fecha y hora fin*</label>
        <input data-change="hiden-error" class="typeahead form-control pickadate-translations" style="width: 60%;display: inline-block;background-color: #3a7f56!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{isset($scheduling->end_front)? ucwords($scheduling->end_front->format('d F, Y')) : '' }}" name="end"  placeholder="Fecha" autocomplete="no">
        <input data-change="hiden-error" type="text" class="form-control timepickers" style="width: 40%;display: inline-block;background-color: #4CAF50!important;color: #fff!important;padding: 5px;text-align: center;box-shadow: none!important;" data-keyup="hiden-error" value="{{isset ($scheduling->end_front) ? strtoupper($scheduling->end_front->format('h:i a')) : '' }}" name="time_end" placeholder="Hora" autocomplete="no">
    </div>
@endif