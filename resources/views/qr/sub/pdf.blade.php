<?php 
ini_set('max_execution_time', 300);
?>
<html>
    <head>  
        <title>Lote QRS #{{$qrLot->id}}</title>
        <link rel="shortcut" href="<?=url("images/default/pdf.png")?>">
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.lite.css")?>">
        <style>
            html{margin: 0px; } 
          
            .qr-content img {
  
            }

            .qr-token{
                position: absolute;
                font-size: 11px;
                display: block;
                line-height: 10.8px;
                margin-top: 80px;
                margin-left: -30px;
                font-weight: 100;
                font-family: 'Franklin Gothic Medium', 'Arial', Arial, sans-serif;
                color: #000;
            }
            .qr-company{
                position: relative;
                margin-top: 80px;
                margin-left: -65px;
                font-size: 11px;
            }
        </style>
    </head>

    <body style="background:#fff;">  
            
        @php $count = 1; @endphp

        @foreach($qrLot->qrs as  $idx =>$qr)
            @php if($count == 1){
                    echo '<table style="width:100%;"><tbody><tr>';
                }   
            @endphp
            <td align="center">
                
                
                 <img style="position:relative;margin-top: 13px;" src="data:image/png;base64, 
                {{ base64_encode(
                    QrCode::format('svg')
                    ->size(60)
                    ->margin(0)
                    //->merge(url('/images/default/icon-50@2x.png'), 0.3, true)
                    //->errorCorrection('H')
                    //->style('round')
                    ->generate($qr->token))
                }}"> 
                <span class="qr-token">{{$qr->token}}<br> Palmera Junior S.A.S</span>
               
            </td>
            @php if($count == 2 ){
                echo '</tr></tbody></table>';
                $count =1;
                }else if($count == 1 && ($idx+1) == $qrLot->qrs->count()) {
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
