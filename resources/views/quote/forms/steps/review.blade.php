<?php
use Illuminate\Database\Eloquent\Collection;
?>
<div class="table-responsive">
    <table class="table table-padded table-xl mb-0 responsive" id="recent-project-table">
        <thead>
            <tr>
                <th class="border-top-0" >Servicio</th>
                <th class="border-top-0">Área</th>
                <th class="border-top-0">Cant.</th>
                <th class="border-top-0">Visitas</th>
                <th class="border-top-0">Control</th>
                <th class="border-top-0">Observaciones</th>
                <th class="border-top-0 text-right">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
                <tr>
                    <td class="nopadding-td"  style="width: 20%;">
                        <div class="row">
                            <div class="col-1">
                                <i class="{{$service['avatar']}} icon-opacity review-service-icon"></i>
                            </div>
                            <div class="col-10">
                                &nbsp;<b>{{$service['name']}}</b>
                            </div>
                        </div>
                    </td>

                    <td class="text-truncate">
                        {{$service['detailService']->zone}}
                    </td>
                    <td>
                        {{$service['quantity']}}
                    </td>
                    <td>
                        {{$service['visits']}}
                    </td>
                    <td>
                        {{collect($service['infestations'])->implode('name',', ')}}
                    </td>
                    <td style="width: 25%;">
                        {{$service['observation']}}
                    </td>
                    <td class="text-right nopadding-td" style="padding-right: 10px!important;">
                        <input type="text" class="form-control readonly text-right transparent" readonly data-format="1" value="$ {{number_format($service['price'],0,".",",")}}" />
                    </td>
                </tr>
            @endforeach
            <tr class="bg-lighten-1 font-weight-bold subtotal" style="{{isset($quote->has_discount) && $quote->has_discount ? '':'display:none;'}}" >
                <td>Subtotal</td>
                <td class="text-right nopadding-td" style="padding-right: 10px!important;"  colspan=6>
                    <input id="subtotal" type="text" class="form-control readonly text-right" readonly data-format="1"   value="{{number_format($services->sum('price'),0,".",",")}}" />
                </td>
            </tr>
            <tr class="discount" style="{{isset($quote->has_discount) && $quote->has_discount ? '':'display:none;'}}">
                <td class="subtotal" colspan=3>Descuento</td>
                <td colspan=4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_percent">En porcentaje</label>
                                <input type="text" class="form-control" data-format="2" name="discount_percent" placeholder="%" value="{{isset($quote->discount_percent) && $quote->discount_percent ? number_format($quote->discount_percent,0,'.',',') : ''}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_value">En valor</label>
                                <input type="text" class="form-control"  data-format="1" name="discount_value" placeholder="$" value="{{isset($quote->discount_value) && $quote->discount_value ? number_format($quote->discount_value,0,'.',',') : ''}}">
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-lighten-1 font-weight-bold">
                <td class="total">Total</td>
                <td class="text-right  nopadding-td" style="padding-right: 10px!important;" colspan=6>
                    <span>$ <span id="total">{{( number_format($services->sum('price'),0,".",","))}}</span></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div align="right">
    <div class="row skin skin-square">
        <div class="col-md-12 col-sm-12">
            <fieldset>
                <input type="checkbox" name="has_discount" id="input-radio-11" value="1" required {{isset($quote->has_discount) && $quote->has_discount? 'checked':''}}>
                <label for="input-radio-11" style="margin-right: 20px;">Aplicar descuento</label>
            </fieldset>
        </div>
    </div>
</div>