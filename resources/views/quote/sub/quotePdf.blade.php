<html>
    <head>  
        <title>Cotización #{{$quote->consecutive}} {{$quote->crm->name}}</title>
        <link rel="shortcut" href="<?=url("images/default/pdf.png")?>">
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.css")?>">
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap-extended.css")?>">
        <link rel="stylesheet" type="text/css" href="<?=url("template/assets/icons/svg/icon.css")?>">
        <style>
            .page_break { page-break-before: always; }
            table{font-size: 13px!important;}
            html, body{background: #fff!important;}
            th{font-size: 13px!important;}
            td, th {padding: 0.3rem 1rem!important;}
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            .avatars {border-radius: 25px;object-fit: cover;width: 50px;height: 50px;}
        </style>
    </head>

    <body>
        <div>
            <!-- Invoice Company Details -->
            <div id="invoice-company-details" class="row">
                <div class="col-md-6 col-sm-12 text-left text-md-left">
                    <img src="<?=url("template/app-assets/images/logo/logo.png")?>"
                        alt="company logo" class="mb-2" width="120" >

                    <img src="{{$quote->commercial->full_avatar}}" class="avatars" style="position:absolute; top:110px; left:-0px;"/>
                    <ul class="px-0 list-unstyled" style="margin-left:50px;">
                        <li class="text-bold-700" style="padding:0px;padding;margin:0px;height: 18px;">Palmera Junior, Manejo Integrado De Plagas</li>
                    <li style="padding:0px;padding;margin:0px;height: 18px;">Asesor(a): {{$quote->commercial->name}}</li>
                        <li style="padding:0px;margin:0px;height: 18px;">{{$quote->headquarter->address}}, {{$quote->headquarter->city->name}}</li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <h2>Cotización #<?= $quote->id ?></h2>
                    <ul class="px-0 list-unstyled" style="font-size: 12px; margin-top: -10px;">
                        <li style="padding:0px;padding;margin:0px;height: 18px;">Creada <?= \Carbon\Carbon::parse($quote->created_at)->format('d/m/Y') ?></li>
                        <li class="" style="padding:0px;padding;margin:0px;height: 18px;">Vigencia <?= \Carbon\Carbon::parse($quote->expire_date)->format('d/m/Y') ?></li>
                    </ul>
                    <br>
                    <img src="{{$quote->crm->avatar}}" class="avatars" style="position:absolute; top:100px; right:-0px;"/>
                    <ul class="px-0 list-unstyled" style="margin-top: -20px; margin-right: 50px;">
                        <li class="text-bold-700" style="padding:0px;padding;margin:0px;height: 18px;">{{$quote->crm->name}}</li>
                        <li class="" style="padding:0px;padding;margin:0px;height: 18px;">{{$quote->office->name}}</li>
                    <li style="padding:0px;padding;margin:0px;height: 18px;">{{$quote->office->address}}, {{$quote->office->city->name}}</li>
                    </ul>
                    
                </div>
            </div>
            <!-- Invoice Customer Details -->
            <!--/ Invoice Customer Details -->
           
            <!--/ Invoice Company Details -->

            

            <!-- Invoice Items Details -->
            <div class="col-md-10 offset-md-1" id="step4table" style="margin-top: -140px;">
                <div class="table-responsive" >
                <table class="table table-padded table-xl mb-0 responsive" id="recent-project-table">
                    <thead>
                        <tr>
                            <th class="border-top-0">Servicio</th>
                            <th class="border-top-0">Cobertura</th>
                            <th class="border-top-0">visitas</th>
                            <th class="border-top-0">Control</th>
                            <th class="border-top-0">Observaciones</th>
                            <th class="border-top-0 text-right">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quote->services as $service)
                        <tr>
                            <td class="nopadding-td" style="width: 25%;">
                                <div class="row">
                                    <div class="col-3">
                                        <i class="custom-package2 icon-opacity review-service-icon"></i>
                                    </div>
                                    <div class="col-9">
                                        <b>{{$service->service->name}}</b>
                                    </div>
                                </div>
                            </td>
        
                            <td class="text-truncate">
                                {{$service->coverage}}
                            </td>
                            <td class="text-truncate">
                                {{$service->visits}}
                            </td>
                            <td>
                                {{$service->infestations->pluck('infestation.name')->flatten()->implode(', ')}}
                            </td>
                            <td style="width: 25%;">
                                {{$service->observation}}
                            </td>
                            <td class="text-right nopadding-td" style="padding-right: 10px!important;">
                                ${{number_format($service->price,0,'.',',')}}
                            </td>
                        </tr>
                        @endforeach

                        <tr class="bg-lighten-1 font-weight-bold subtotal" style="{{isset($quote->has_discount) && $quote->has_discount ? '':'display:none;'}}" >
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td class="subtotal text-right nopadding-td" style="border-left: 1px solid #ddd">Subtotal</td>
                            <td class="text-right nopadding-td">
                               ${{number_format($quote->services->sum('price'),0,".",",")}}
                            </td>
                        </tr>
                        <tr class="discount" style="{{isset($quote->has_discount) && $quote->has_discount ? '':'display:none;'}}">
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td style="border:none;"> </td>
                            <td class="subtotal text-right nopadding-td" style="border-left: 1px solid #ddd;"><b>Descuento ({{isset($quote->discount_percent) && $quote->discount_percent ? number_format($quote->discount_percent,0,'.',',') : ''}})%</b></td>
                            <td class="text-right nopadding-td discount-value">
                                <b>$ -{{isset($quote->discount_value) && $quote->discount_value ? number_format($quote->discount_value,0,'.',',') : ''}} </b>
                            </td>
                        </tr>
                        <tr class="bg-lighten-1 font-weight-bold" style="background: #5bc55f;color:#fff;border:none">
                            <td class="total text-right" style="padding: 10px!important;border:none" colspan="5">Total</td>
                            <td class="text-right  nopadding-td" style="padding: 10px!important;border:none">
                                <span>$<span id="total">{{( isset($quote->total) && $quote->total ? number_format($quote->total,0,".",",") : number_format($quote->services->sum('price'),0,".",","))}}</span></span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div align="right">
                <div class="row skin skin-square">
                    <div class="col-md-12 col-sm-12">
                        <fieldset>
                            <div class="icheckbox_square-red" style="position: relative;"><input type="checkbox" name="has_discount" id="input-radio-11" value="1" required="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            <label for="input-radio-11" style="margin-right: 20px;">Aplicar descuento</label>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <hr>
                <p style="color:#999; font-size:14px;">*Cotización diseñada exclusivamente para <b>{{$quote->contact->name}}</b>, dónde se detallan  los productos/servicios que hacen parte del paquete a adquirir, esta podría estar sujeta a ajustes posteriores según acuerdo de aprobación o bien por negociación realizada con el asesor correspondiente.<br><br></p>
            </div>
            <div class="col-md-10 offset-md-1" id="step4table"><div class="table-responsive">
            <p style="color:#999; font-size:14px;">{{$quote->diagnostic ? 'Ten presente que uno o varios servicios de esta cotización requieren un dignóstico, y en caso de aprobarse, uno de nuestros coordinadores MIP, programará una visita para realizarlo antes de programar la ejecución de estos.': ''}}</p>
            </div>
        </div>
        </div>
    </body>
</html>