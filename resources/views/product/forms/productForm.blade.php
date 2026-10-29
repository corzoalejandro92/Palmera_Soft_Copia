<div class="modal-header">
    <h6 class="modal-title">{{(isset($product->id)?'EDITAR PRODUCTO':'CREAR PRODUCTO')}}</h6>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<script>
    var setList = [];
        var defaultValues = {
            'enabled' : 1
        };
    </script>
    @if(isset($product->id))
    <script>
        setList = <?=json_encode($product->detailProducts->toArray()); ?>;
    </script>
    @endif

<div class="modal-body">
    <form id="product" action="{{isset($product->id)?'products/update':'products/save'}}" enctype="multipart/form-data">
        {{ method_field( isset($product->id)?'POST':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $product->id ?? ''; ?>" name="id" />
        <div class="container">
            <div class="form-body row">

                <div class="col-12 col-lg-3 mb-1">
                    <div>
                        <input type="file" data-input="fileImage" accept="image/*" onchange="loadImage(this)"
                            class="d-none" />
                        <div data-upload="file" data-form="product" class="pointer text-center">
                            <img class="avatar md pointer"
                                src="<?= url($product->avatar ?? 'images/default/product.png' ); ?>" />
                            <input type="hidden" value="<?= $product->avatar ?? 'images/default/product.png'; ?>"
                                name="avatar" />
                            <div class="mt-1">
                                <button type="button" class="btn btn-outline-success btn-sm round">Cargar
                                    imagen</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-lg-7 col-xl-7 col-md-7">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <h6>Tipo</h6>
                            <select class="form-control" id="category" name="category" data-change="hiden-error">
                                <option value="">--Seleccionar--</option>
                                <?php if (isset($product->categorys)): ?>
                                <option value="<?= $product->categorys->id; ?>" selected=""><?= $product->categorys->name; ?>
                                </option>
                                <?php endif; ?>
                            </select>
                        </div>
        
                        <div class="form-group ">
                            <h6>Nombre</h6>
                            <input type="text" class="form-control round" data-keyup="hiden-error" placeholder="Ingrese el nombre del producto"
                                name="name" value="{{ $product->name ?? ''}}">
                        </div>
                        <div class="form-group">
                            <h6>Código Foxcon </h6>
                            <input type="text" data-keyup="hiden-error" class="form-control round"
                                placeholder="Ingrese el consecutivo del producto" name="code"
                                value="{{ $product->code ?? ''}}">
                        </div>
                    </div>
                </div>
                <div class="row col-xl-1 col-lg-1">
                    <div class="match-height">
                        <div class="card-body" style="top:-18px;">
                            <h6>Activo</h6>
                            <div class="row icheck_minimal skin">
                                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                    <fieldset>
                                        <input name="enabled" type="checkbox" value="1"
                                        {{ isset( $product->enabled) ? ($product->enabled == "1" ? 'checked': ''):'checked'}}>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                    <h6>Descripción</h6>
                    <textarea type="text" class="form-control round" placeholder="Ingrese la descripción del producto"
                        name="description">{{ $product->description ?? ''}}</textarea>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                    <h6>Superficie de Captura (cm2)</h6>
                    <input class="form-control round" min="0" value="{{ $product->capture_surface ?? ''}}" type="number" name="capture_surface" placeholder="Ingrese la Superficie de Captura">
                </div>
        </div>

        <!-- Formulario de detalle de producto -->
        <div class="detailForm">
            
        </div>

        <!-- Formulario de especification del producto -->
        <div class="specification">
            
        </div>

         <!-- documentos del producto -->
         <div class="documents">
            
        </div>

        <!-- Metodos del producto -->
         <div class="methods">
            
        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($product->id))
        @can('products.edit')
           <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('products.create')
        <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
        @endcan
    @endif
</div>