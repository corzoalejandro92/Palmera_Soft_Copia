<?php
    set_time_limit(40000);
    $background = public_path("/images/default/quote_background.png");
    $pages =  explode('<div class="cke-page">',$quote->format_html);
?>
<html>
    <head>  
        <title>Cotización #{{$quote->consecutive}} {{$quote->crm->name}}</title>
        
        <style>
            body {
               
            }

            table{font-size: 13px!important;}
            html, body{background: #fff!important; font-family: sans-serif!important}
            th{font-size: 13px!important;}
            td, th {padding: 0.3rem 1rem!important;}
            h2, .discount-value{font-family: sans-serif; color:#ff0808;}
            .avatars {border-radius: 25px;object-fit: cover;width: 50px;height: 50px;}
            @page {
            margin: 190px 100px 15px 100px!important;
            padding: 0px 0px 0px 0px !important;
            }
            #header {  
                background-image: url({{$background}})!important;
                background-repeat: no-repeat;
                position: fixed;
                top: -180px;
                left: -100px;
                width: 110%;
                height: 180%;
            
            }

            #footer {  
                position: fixed;
                bottom: 90px;
                left: -15px;
                line-height: 15px;
                color: #c3c3c3;
            
            }

            div.content{
               z-index: 10000000000;
            }
        </style>
    </head>

    <body>
        <div id="header"></div>
        <div id="footer">
            <b>{{$headquarter->address}}</b><br>
            <b>tel. {{$headquarter->phone}}, cel. {{$headquarter->cell_phone}}</b><br>
            <b>e-mail: {{$headquarter->email}}</b><br>
        </div>
        <div class="content">
            <p  style="text-align:right; font-weight:bolder;font-family:monospace!important;color:rgb(160, 1, 1)!important;"><code>COTIZACIÓN <span style="color:rgb(160, 1, 1)!important;">{{$quote->code}}</span></code></p>
            @foreach ($pages as $pageCount => $page )
            @if(!empty($page))
            <div class="cke-page" style="{{($pageCount+1) >= count($pages) ? '': 'page-break-after:always;'}}"> 
                {!!preg_replace('/>\s+</', "><", $page)!!}
            @endif
            @endforeach
        </div></body></html>