@php 
    //convertir logo en base64 para que aparezca correctamente en los correos
    //$imgFile = storage_path("template/app-assets/images/logo/logo.png");
    //$imgData = base64_encode(file_get_contents($imgFile));
    //$src = 'data:image/png;base64,'.$imgData;

@endphp
<tr>
    <td class="header">
        <a href="{{ $url }}" style="font-weight:100!important; color: #bbbfc3!important;">
            <div class="text-center mb-1">
                <img style="width: 120px;" src="https://pj.swebsas.com/template/app-assets/images/logo/logo.png" alt="">
            </div>
            {{ $slot }}
        </a>
    </td>
</tr>
