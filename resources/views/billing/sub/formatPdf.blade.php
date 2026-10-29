<html>
    <head>  
        <title>Proforma #{{$billing->number_billing}} {{$billing->crm->name}}</title>
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.lite.css")?>">
        <style>
            .page_break { page-break-before: always; }
            table{font-size: 13px!important;}
            html, body{background: #fff!important;}
            th{font-size: 13px!important;}
            td, th {padding: 0.3rem 1rem!important;}
            h2, .discount-value{font-family: sans-serif; color:#ff5103;}
            .avatars {border-radius: 25px;object-fit: cover;width: 50px;height: 50px;}
        </style>
    </head>

    <body>
        <div>
            <!-- Invoice Company Details -->
            <div class="row">
                <div class="col-md-6 col-sm-12 text-right">
                    <h2>Proforma #<?= $billing->number_billing ?></h2>
                    <ul class="px-0 list-unstyled" style="font-size: 18px; margin-top: 0px;">
                        <li style="padding:0px;padding;margin:0px;height: 18px;">Cliente: {{$billing->crm->name}} </li>
                    </ul>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 text-center">
                    <ul class="px-0 list-unstyled" style="font-size: 20px; position: relative;">
                        <li style="padding:0px;padding;margin:0px;height: 18px;">Facturado</li>
                    </ul>
                    <br>
                    <br>
                </div>
            </div>
            
            
            <!-- Invoice Customer Details -->
            <!--/ Invoice Customer Details -->
           
            <!--/ Invoice Company Details -->

            

            <!-- Invoice Items Details -->
            <div class="col-md-10 offset-md-1" id="step4table">
                <div class="table-responsive" >
                <table class="table table-padded table-xl mb-0 responsive" id="recent-project-table">
                    <thead>
                        <tr>
                            <th class="border-top-0 text-center">Servicio</th>
                            <th class="border-top-0 text-center">Cantidad</th>
                            <th class="border-top-0 text-center">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($billing->detailBillings as $detailBilling)
                        <tr>
                            <td class="text-center"> {{$detailBilling->name}}</td>
                            <td class="text-center"> {{$detailBilling->quantity}}</td>
                            <td class="text-center"> <b>$<?= number_format($detailBilling->valor,0,'.',',') ?></b></td>

                        </tr>
                        @endforeach


                        <tr class="bg-lighten-1 font-weight-bold" style="background: #5bc55f;color:#fff;border:none">
                            <td class="total text-right" style="padding: 10px!important;border:none" colspan="5">Total <b>$<?= number_format($billing->total,0,'.',',') ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-10 offset-md-1" id="step4table"><div class="table-responsive">
            <p style="color:#999; font-size:14px;"></p>
            </div>
        </div>
        </div>
    </body>
</html>

