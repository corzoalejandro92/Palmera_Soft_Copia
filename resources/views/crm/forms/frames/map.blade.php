<div class="modal-header">
    <h5 class="modal-title">Definir ubucación en el mapa</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>



<div class="modal-body">

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="form-group m-form__group row">
                    <div class="col-12">
                        <input class="form-control transparent" name="map-address" disabled value="{{$address}} {{$city}}">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="map"></div>
            </div>
        </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-danger round float-left reset-pin" >Resetear ubicación</button>
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Utilizar esta ubicación</button>
</div>
