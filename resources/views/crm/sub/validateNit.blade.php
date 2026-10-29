<div class="modal-header">
    <h5 class="modal-title">ENCONTRAMOS COINCIDENCIAS</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body" >
    <div class="container mt-1">
        <h3>Encontramos clientes exitentes con el mismo CC/NIT que has ingresado:</h3>
        <form action="" method="post" class="mb-3">
            @foreach ($crms as $crm)
            <hr>
                <label>
                    <input type="radio" name="customerExist" value="{{ $crm->id }}">
                    <span><b>{{ $crm->name }}</b></span><small> /{{ $crm->commercial_name }}</small><br>
                    <small>CC/NIT: <span style="background-color:yellow">{{ $crm->document }}</span></small><br>
                    <small>Creada en: {{ $crm->offices->first()->headquarter->name }}</small><br>
                    @if($crm->customer_national) <small><span class="badge badge-success">Cliente Nacional</span></small><br> @endif
                    <small><i class="las la-building"></i>{{ $crm->offices->count() }} Sucursal{{ $crm->offices->count() == 1 ? '':'es' }}</small>
                    <span class="select"></span>
                </label>
            @endforeach
        </form>
        <div class="alert alert-info" role="alert">
            Ten presente que si la sucursal seleccionada no es una sucursal nacional, esta pasará a serlo una vez continues
          </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @can('crms.create')
    <button type="button" class="btn btn-success round" data-click="useThisCustomer">Usar cliente seleccionado</button>
    @endcan
</div>

<style>
    .container {
    text-align: left;
    font-family: sans-serif;
}
select {
    width: 100%;
    min-height: 150px;
    margin-bottom: 20px;
}
input[type="submit"] {
    display: block;
    margin: 20px auto;
}
label {
    display: block;
    position: relative;
    cursor: pointer;
    font-size: 18px;
    padding-left: 46px;
    margin-bottom: 15px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
label input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}
.select {
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    position: absolute;
    border-radius: 50%;
    background-color: #cccccc;
}
label:hover input~.select {
    background-color: #ccc;
}
label input:checked~.select {
    background-color: #1A33FF;
}
.select:after {
    content: "";
    position: absolute;
    display: none;
}
label input:checked~.select:after {
    display: block;
}
label .select:after {
    top: 9px;
    left: 9px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
}
</style>