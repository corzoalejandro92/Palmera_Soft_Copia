<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">

    <form id="billing">
        {{ method_field( isset($billing->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $billing->id ?? ''; ?>" name="id" />
        <!-- contenido del modal -->
        <section>
            <div id="invoice-template" class="card-body">
                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-md-6 col-sm-12 text-left text-md-left">
                        <img src="<?=url("template/app-assets/images/logo/logo.png")?>" alt="company logo"
                            class="mb-2" width="150">
                        <ul class="px-0 list-unstyled">
                            <li class="text-bold-700">{{config('app.name')}}</li>
                            <li>{{$headquearter->name ?? ''}},</li>
                            <li>{{$headquearter->address ?? ''}}</li>
                            <li>{{$headquearter->email ?? ''}}</li>
                            <li>Tel. {{$headquearter->phone ?? ''}}</li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center text-md-right">
                        <h2>PROFORMA</h2>
                        <div class="form-group row" style="left: 64px;position: relative;">
                            <label class="col-3 col-form-label">No.</label>
                            <div class="col-7 text-center">
                                <input class="form-control text-center"
                                    value="{{$billing->number_billing ?? $numberProforma}}" name="number_proforma"
                                    type="number" readonly>
                            </div>
                        </div>
                        <div class="form-group row" style="left: 64px;position: relative;">
                            <label class="col-3 col-form-label">Fecha.</label>
                            <div class="col-7 text-center">
                                <input class="form-control text-center"
                                    value="{{ Carbon::parse($schedule->date_billing ?? '')->format("Y-m-d")}}" name="date_billing"
                                    type="date" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Invoice Company Details -->
                <div id="invoice-customer-details" class="row">
                    <div class="col-md-6 col-sm-12">
                        <p class="text-muted"></p>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center text-md-right">
                        <p class="text-muted"></p>
                        <ul class="px-0 list-unstyled">
                            <div class="form-group">
                                <h5>Cliente</h5>
                                <select class="select2 form-control" id="customer" name="crm_id"
                                    data-change="hiden-error" style="width:100%;">
                                    @if(isset($schedule->crm))
                                    <option value="{{$schedule->crm->id}}" selected>{{$schedule->crm->name}}</option>
                                    @endif
                                </select>
                            </div>
                        </ul>
                    </div>
                </div>

                <!-- Invoice Items Details -->
                <div id="invoice-items-details" class="pt-2">
                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <table class="table stripe tbl-documents">
                                <thead>
                                    <tr class="th_title">
                                        <th>Servicio</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        @if(isset($billing) && $schedule->detailBillings)

                                        @else
                                        <th style="width: 10px;"></th>
                                        @endif
                                    </tr>
                                <tbody>
                                @if(isset($schedules))
                                @foreach($schedules as $indx => $schedule)
                                    @php
                                    $key = uniqid();
                                    $order = true;
                                    @endphp
                                    @include('billing.forms.frames.billing_new_row',compact(['key','schedule']))
                                @endforeach
                                @else
                                    @php $key = uniqid(); $schedule = null; @endphp
                                    @include('billing.forms.frames.billing_new_row',compact(['key','schedule']))
                                @endif
                                </tbody>
                                @if(isset($billing) && $schedule->detailBillings)

                                @else
                                <tfoot>
                                    
                                    <tr>
                                        <th colspan="1" class="add_row_th" style="width: 10px;">
                                            <a class="btn btn-outline-teal" data-click="addRow">
                                                <i class="la la-plus-circle"></i>
                                            </a>
                                            @include('layouts/loading/min')
                                        </th>
                                        <th colspan="3"></th>
                                    </tr>
                                    
                                </tfoot>
                                @endif
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div style="width: 100%;position: relative;left: 23rem;">
                            <div class="col-md-7 col-sm-12">
                                <p class="lead">Total a pagar</p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>TOTAL</td>
                                                <td class="pink text-right" style="font-size: 19px;"> $<span class="pink text-right" style="font-size: 19px;" id="total">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="total" value="0" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer -->
                <!-- <div id="invoice-footer">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        <h6>Términos y Condiciones</h6>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci incidunt,
                            tenetur a repellat libero quam fugiat voluptatibus voluptatum earum dicta.
                            Temporibus hic recusandae quod facilis exercitationem. Sequi minus nobis
                            perferendis..</p>
                    </div>
                    <div class="col-md-5 col-sm-12 text-center">
                        <button type="button" class="btn btn-info btn-lg my-1"><i
                                class="la la-paper-plane-o"></i> Enviar Proforma</button>
                    </div>
                </div>
            </div> -->
                <!--/ Invoice Footer -->

            </div>
        </section>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($billing->id))
        @can('billings.edit')
           <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('billings.create')
           <button type="button" class="btn btn-success round" data-click="save">Crear</button>
        @endcan
    @endif
</div>
