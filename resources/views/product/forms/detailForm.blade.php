<hr>
<h5 class="modal-title mb-1 text-bold-700">Detalle del producto</h5>
<div class="container">
    <div class="form-body row">
        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <div class="form-group">
                <h6>Unidad de medida</h6>
                <select class="form-control select2" id="unit" name="unit">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->units)): ?>
                    <option value="<?= $product->units->id; ?>" selected=""><?= $product->units->name; ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>



        <!-- <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <div class="form-group">
                <h6>Aplicación</h6>
                <select class="select2 form-control" id="application" name="application">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->applications)): ?>
                    <option value="<?= $product->applications->id; ?>" selected=""><?= $product->applications->name; ?>
                    </option>
                    <?php endif; ?>
                </select>
            </div>
        </div> -->

        <div class="row match-height">
            <div class="col-xl-12 col-lg-12">
                <div class="card-body" style="top:-18px;">
                    <h6>Tóxico</h6>
                    <div class="row icheck_minimal skin">
                        <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1">
                            <fieldset>
                                <input name="toxico" type="checkbox" value="1"
                                {{ isset( $product->toxico) ? ($product->toxico == "1" ? 'checked': ''):''}}>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!--
        <div class="col-12 col-lg-3">
            <div>
                <input type="file" data-input="fileImageM" accept="image/*" onchange="loadImageM(this)" class="d-none" />
                <div data-upload="fileM" class="pointer text-center display-hidden">
                    <img class="avatarM avatarMechanism md pointer"
                        src="<? url($product->mechanisms->avatar ?? 'images/default/mecanismo.jpg' ); ?>" />
                    <input type="hidden"
                        value="<? ($product->mechanisms->avatar ?? 'images/default/mecanismo.jpg'); ?>"
                        name="avatar_mechanism" />
                    <div class="btn-file">
                        <button type="button" class="btn btn-outline-success btn-sm round">Cargar
                            imagen</button>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
    
    <div class="row">
        <!---- <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <div class="form-group">
                <h6>Categoría</h6>
                <select class="form-control" id="category" name="category">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->categorys)): ?>
                    <option value="<?= $product->categorys->id; ?>" selected=""><?= $product->categorys->name; ?>
                    </option>
                    <?php endif; ?>
                </select>
            </div>
        </div> -->


        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 display-hidden">
            <div class="form-group">
                <h6>Tipo de mecanismo</h6>
                <select class="select2 form-control" id="mechanism" name="mechanism">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->mechanisms)): ?>
                    <option value="<?= $product->mechanisms->id; ?>" selected=""><?= $product->mechanisms->name; ?>
                    </option>
                    <?php endif; ?>
                </select>
            </div>
        </div>


        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 {{isset($product->category_id) ? ($product->category_id != 1 ? 'display-hidden' : '') : 'display-hidden'}}">
            <div class="form-group">
                <h6>Productos Relacionados</h6>
                <select class="select2 form-control" multiple name="related_products[]" placeholder="--Seleccionar--">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->products)): ?>
                    @foreach ($product->products as $relatedProduct)
                    <option value="<?= $relatedProduct->id; ?>" selected=""><?= $relatedProduct->name; ?></option>
                    @endforeach
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 {{isset($product->category_id) ? ($product->category_id != 1 ? 'display-hidden' : '') : 'display-hidden'}}">
            <div class="form-group colordiv">
                <h6>Color</h6>
                <input class="form-control colorpicker" id="color" name="color" value="{{ isset($product->category_id) && $product->category_id == 1 ? ($product->color ?? '#ff2626') : '' }}">
            </div>
        </div>
    </div>

    <div class="row">
        <!--<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <div class="form-group">
                <h6>Controles a los que aplica</h6>
                <select class="form-control select2" id="infestation" placeholder="--Seleccionar--" multiple name="infestations[]">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($product->infestations)): ?>
                    @foreach ($product->infestations as $infestation)
                    <option value="<?= $infestation->id; ?>" selected=""><?= $infestation->name; ?></option>
                    @endforeach
                    <?php endif; ?>
                </select>
            </div>
        </div> -->

        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <h6>Precio</h6>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                </div>
                <input type="text" name="price" id="price" data-format="1" value="{{ number_format(($product->price ?? 0),0,".",",")}}" class="form-control" placeholder="Ingrese el valor">
            </div>
        </div>
    </div>
    
</div>
</div>