<?php 
ini_set('max_execution_time', 300);
$officeName = strlen($office->name) > 15 ? substr($office->name,0,15)."..." : $office->name;
$crmName =      strlen($crm->name) > 30 ? substr($crm->name,0,15)."..." : $crm->name;
?>
<html>
    <head>  
        <title> QRS {{$crmName}} {{$officeName}}</title>
        <style>
            html{margin: 0px; } 
          
            .qr-content img {
  
            }

            .qr-token{
                position: absolute;
                font-size: 11px;
                display: block;
                margin-top: 80px;
                color: #000;
                position: absolute;
                margin-left: auto;
                margin-right: auto;
                left: 0;
                right: 0;
                text-align: center;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif';
                line-height: 1.1;
            }
            .qr-company{
                position: absolute;
                display: block;
                margin-left: auto;
                margin-right: auto;
                left: 0;
                right: 0;
                color: #000;
                text-align: center;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif';
                font-size: 7px;
                transform: rotate(270deg);
            }
            .qr-company-right{
                position: absolute;
                display: block;
                margin-left: auto;
                margin-right: auto;
                left: 45px;
                right: 0;
                color: #000;
                text-align: center;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif';
                font-size: 7px;
                transform: rotate(-270deg);
                width: 60;
            }
        </style>
    </head>

    <body style="background:#fff;">  
            
        @php $count = 1; @endphp

        @foreach($fileExtends as  $idx => $qr)
            @php if($count == 1){
                    echo '<table style="width:100%;"><tbody><tr>';
                }   
            @endphp
            <td align="center" style="position: relative">
                
                <span class="qr-company">PALMERA JUNIOR </span>
                <span class="qr-company-right"> {{strtoupper($crmName)}} </span>
                 <img style="position:relative;margin-top: 14px;" src="data:image/svg+xml;base64, 
                    {{ base64_encode(
                        QrCode::format('svg')
                        ->size(60)
                        ->margin(0)
                        //->merge(url('/images/default/icon-50@2x.png'), 0.3, true)
                        //->errorCorrection('H')
                        //->style('round')
                        ->generate($qr->id))
                    }}"> 
                <span class="qr-token" ><b>{{$qr->extendible->slug}}#{{$qr->num}}</b><br>
                    <span style="font-size: 8px">{{ strtoupper($officeName)}}</span>
                </span>
               
               
            </td>
            @php if($count == 2 ){
                echo '</tr></tbody></table>';
                $count =1;
                }else if($count == 1 && ($idx+1) == $fileExtends->count()) {
                    echo '<td style="width:50%"></td></tr></tbody></table>';
                    break;
                }
                else{
                    $count ++;
                }   
            @endphp
        
        @endforeach
        

    </body>
 </html>
 <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {	
               
                window.print();
            });

        </script>
